<template>
    <span>
        <button type="button"
            class="btn btn-success ml-2"
            @click="openChooseFileDialog()">
            Nhập file Excel
        </button>

        <input type="file"
            ref="excelFileInput"
            class="d-none"
            accept=".xlsx"
            @change="importExcel($event)" />

        <a href="/templates/country.xlsx"
            class="ml-2">
            File mẫu
        </a>
    </span>
</template>


<script>
export default {
    data() {
        return {
            // Danh sách dữ liệu khi import Excel
            rows: null,

            // Có import thành công không
            isImportSuccess: true
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

                // Dừng lại
                return;
            }

            // Lấy dữ liệu dòng hiện tại
            const currentRow = this.rows.shift();
            const rowNumber = currentRow.rowNumber;
            const rowData = currentRow.data;
            const code = this.normalizeExcelCellData(rowData, 0);
            const name = this.normalizeExcelCellData(rowData, 1);

            // Validate dữ liệu ở client
            const validateErrors = [];

            const validateCode = CV.validateByValueAndValidation(code, CV.parseValidationFromString('required|maxLength:100'));
            if (validateCode) {
                validateErrors.push('<b>Mã</b>: ' + validateCode);
            }

            const validateName = CV.validateByValueAndValidation(name, CV.parseValidationFromString('required|maxLength:100'));
            if (validateName) {
                validateErrors.push('<b>Tên</b>: ' + validateName);
            }

            // Nếu có lỗi validate thì thông báo
            if (validateErrors.length) {
                const messages = validateErrors.join('<br />');
                noti.confirm('Lỗi ở dòng ' + rowNumber + '.<br />' + messages + '.<br />Bạn có muốn tiếp tục?', () => {
                    // Tiếp tục dòng nữa
                    this.importSingleRow();
                });

                // Tìm kiếm lại
                this.$emit('done');

                // Dừng lại
                return;
            }

            // Gọi lên server
            const params = {
                code: code,
                name: name
            };
            const { data } = await axios.post('/country/store', params);

            // Tìm kiếm lại
            this.$emit('done');

            // Xử lý dữ liệu trả về
            if (data.code == 0) {
                // Tiếp tục dòng nữa
                this.importSingleRow();
            } else if (data.code == 422) {
                noti.confirm('Lỗi ở dòng ' + rowNumber + '.<br />Bạn có muốn tiếp tục?', () => {
                    // Tiếp tục dòng nữa
                    this.importSingleRow();
                });
            } else {
                this.isImportSuccess = false;
                noti.error('Đã có lỗi xảy ra');
            }
        }
    }
};
</script>
