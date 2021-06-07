<template>
    <span>
        <div class="position-fixed top-left mt-4 w-100 text-center"
                v-if="totalRow > 0">
            <div class="progress w-50 mx-auto"
                    style="height: 3px">
                <div class="progress-bar"
                        :style="{
                            width: (processedRow * 100 / totalRow) + '%'
                        }"></div>
            </div>

            <div v-if="totalRow > 100"
                    class="mt-2">
                {{formatNumber(processedRow)}} / {{formatNumber(totalRow)}}
            </div>
        </div>

        <button type="button"
                class="btn btn-success ml-2"
                @click="openChooseFileDialog()">
            Nhập file Excel
        </button>

        <input type="file"
                ref="excelFileInput"
                class="d-none"
                accept=".xlsx"
                @change="importExcel($event)"/>

        <a :href="templatePath"
                class="ml-2">
            File mẫu
        </a>
    </span>
</template>


<script>
export default {
    props: {
        validateRow: Function,
        insertRow: Function,
        isDataRow: {
            type: Function,
            default: (rowData) => {
                return true;
            }
        },

        // Đường dẫn file mẫu
        templatePath: String
    },

    data() {
        return {
            // Danh sách dữ liệu khi import Excel
            rows: null,

            // Có import thành công không
            isImportSuccess: true,

            // Tổng số bản ghi
            totalRow: 0,

            // Số bản ghi đã xử lý
            processedRow: 0
        };
    },

    methods: {
        /**
         * Mở hộp thoại chọn file Excel.
         */
        openChooseFileDialog() {
            this.$refs.excelFileInput.click();
        },

        /**
         * Import dữ liệu Excel.
         */
        importExcel(evt) {
            const reader = new FileReader();
            reader.addEventListener('load', () => {
                const arrayBuffer = reader.result;
                this.processImportFile(arrayBuffer);

                // Reset lại trường, để có thể import lại file (bắt sự kiện change)
                evt.target.value = '';
            });
            reader.readAsArrayBuffer(evt.target.files[0]);
        },

        /**
         * Xử lý import.
         */
        async processImportFile(arrayBuffer) {
            const workbook = new ExcelJS.Workbook();
            await workbook.xlsx.load(arrayBuffer);

            // Đọc sheet đầu tiên
            const worksheet = workbook.worksheets[0];

            // Xử lý từng dòng
            const rows = [];
            worksheet.eachRow((row, rowNumber) => {
                // Dữ liệu bắt đầu từ phần tử thứ nhất
                if (rowNumber > 1) {
                    rows.push({
                        rowNumber: rowNumber,
                        data: row.values.slice(1)
                    });
                }
            });

            this.processImportData(rows);
        },

        /**
         * Xử lý dữ liệu file Excel.
         */
        processImportData(rows) {
            if (rows.length == 0) {
                noti.error('File Excel không có dữ liệu');
                return;
            }

            this.rows = rows;
            this.isImportSuccess = true;
            this.totalRow = this.rows.length;
            this.processedRow = 0;

            // Bắt đầu import
            this.importSingleRow();
        },

        /**
         * Import từng dòng dữ liệu.
         */
        async importSingleRow() {
            // Nếu đã import xong
            if (this.rows.length == 0) {
                // Thông báo thành công
                if (this.isImportSuccess) {
                    noti.success('Đã import xong');
                }

                // Tìm kiếm lại
                this.$emit('done');

                // Ẩn progress bar
                this.totalRow = 0;

                // Dừng lại
                return;
            }

            // Lấy dữ liệu dòng hiện tại
            const currentRow = this.rows.shift();
            const rowNumber = currentRow.rowNumber;
            const rowData = currentRow.data;
            rowData.forEach((value, idx) => {
                rowData[idx] = this.normalizeExcelCellData(rowData, idx);
            });

            // Nếu không phải dòng dữ liệu thì bỏ qua và import tiếp
            if (!this.isDataRow(rowData)) {
                this.processedRow++;
                this.importSingleRow();
                return;
            }

            const validateErrors = this.validateRow(rowData);

            // Nếu có lỗi validate thì thông báo
            if (validateErrors.length) {
                this.processedRow++;
                const messages = validateErrors.join('<br />');
                noti.confirm('Lỗi ở dòng ' + rowNumber + '.<br />' + messages + '.<br />Bạn có muốn tiếp tục?', () => {
                    // Tiếp tục dòng nữa
                    this.importSingleRow();
                });

                // Tìm kiếm lại
                this.$emit('done');

                // Ẩn progress bar
                this.totalRow = 0;

                // Dừng lại
                return;
            }

            // Gọi lên server
            const { data } = await this.insertRow(rowData);
            this.processedRow++;

            // Xử lý dữ liệu trả về
            if (data.code == 0) {
                // Tiếp tục dòng nữa
                this.importSingleRow();
            } else if (data.code == 1) {
                noti.confirm('Lỗi ở dòng ' + rowNumber + '.<br />' + data.message + '.<br />Bạn có muốn tiếp tục?', () => {
                    // Tiếp tục dòng nữa
                    this.importSingleRow();
                });
            } else if (data.code == 422) {
                noti.confirm('Lỗi ở dòng ' + rowNumber + '.<br />Bạn có muốn tiếp tục?', () => {
                    // Tiếp tục dòng nữa
                    this.importSingleRow();
                });
            } else {
                this.isImportSuccess = false;
                noti.error('Đã có lỗi xảy ra');

                // Tìm kiếm lại
                this.$emit('done');

                // Ẩn progress bar
                this.totalRow = 0;
            }
        }
    }
};
</script>


<style lang="scss" scoped>
.top-left {
    top: 0;
    left: 0;
}

.progress {
    max-width: 500px;
}
</style>
