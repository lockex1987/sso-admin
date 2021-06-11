// # Vue them mixin currentUser va cac ham can, hasRole
export default {
    computed: {
        ...Vuex.mapState({
            loginUser: state => state.auth.user
        })
    },

    methods: {
        /**
         * Hiện thị đối tượng moment theo định dạng ngày tháng
         */
        convertMomentToFrontendString(momentObject) {
            if (!momentObject) {
                return '';
            }
            return momentObject.format('DD/MM/YYYY');
        },

        /**
         * Format định dạng ngày tháng.
         */
        formatDate(date) {
            if (!date) {
                return '';
            }
            return moment(date.replace(/\//g, '-')).format('DD/MM/YYYY');
        },

        /**
         * Format định dạng ngày tháng.
         */
        formatDateTime(date, pattern = 'DD/MM/YYYY HH:mm:ss') {
            if (!date) {
                return '';
            }
            return moment(date.replace(/\//g, '-')).format(pattern);
        },

        /**
         * API đang sử dụng định dạng ngày tháng không đúng chuẩn ISO 8601, sử dụng dấu / thay cho dấu -.
         * Hàm này chuyển từ định dạng chuẩn sang định dạng mà API yêu cầu.
         * @param dateStr Xâu ngày tháng theo định dạng chuẩn
         */
        formatWithApiDateTimeFormat(dateStr) {
            if (!dateStr) {
                return '';
            }
            return dateStr.replace(/-/g, '/');
        },

        /**
         * API đang sử dụng định dạng ngày tháng không đúng chuẩn ISO 8601, sử dụng dấu / thay cho dấu -.
         * Hàm này chuyển từ định dạng của API sang định dạng chuẩn.
         * @param dateStr Xâu ngày tháng theo định dạng của API
         */
        normalizeApiDateTimeFormat(dateStr) {
            if (!dateStr) {
                return '';
            }
            return dateStr.replace(/\//g, '-');
        },

        /**
         * Hiển thị số có ngăn cách hàng nghìn.
         * @param {Number} num Số
         */
        formatNumber(num) {
            if (!num) {
                return num;
            }
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        },

        /**
         * Hiển thị làm tròn số.
         * @param {Number} num
         * @param {Number} digits
         */
        prettifyNumber(num, digits) {
            if (!digits) {
                digits = 1;
            }

            const si = [
                { value: 1E18, symbol: 'E' },
                { value: 1E15, symbol: 'P' },
                { value: 1E12, symbol: 'T' },
                { value: 1E9, symbol: 'G' },
                { value: 1E6, symbol: 'M' },
                { value: 1E3, symbol: 'K' }
            ];
            for (let i = 0; i < si.length; i++) {
                if (num >= si[i].value) {
                    const n = (num / si[i].value).toFixed(digits);

                    // Xóa những chữ số 0 đằng sau dấu thập phân
                    // Nếu chỉ để 0+ thì sẽ không xóa được dấu .
                    // Nếu chỉ để \.0+ thì sẽ không xử lý được trường hợp 123.400
                    return n.replace(/\.?0+$/, '') + si[i].symbol;
                }
            }
            return num;
        },

        /**
         * Xuất file Excel.
         */
        async exportExcelCommon(list, columns, sheetName, fileName) {
            // Tạo các đối tượng Workbook và Worksheet
            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet(sheetName);

            // Header
            worksheet.columns = columns;

            // Thêm dữ liệu
            list.forEach(e => {
                worksheet.addRow(e);
            });

            // Style
            // Dòng header
            worksheet.getRow(1).eachCell(cell => {
                cell.font = {
                    bold: true
                };
                cell.alignment = {
                    vertical: 'middle',
                    horizontal: 'center'
                };
                cell.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: { argb: 'FF00B0F0' }
                };
            });

            // Các dòng dữ liệu
            const borderStyle = {
                style: 'thin'
            };
            for (let row = 1; row <= list.length + 1; row++) {
                const rowObject = worksheet.getRow(row);
                for (let colNumber = 1; colNumber <= columns.length; colNumber++) {
                    const cell = rowObject.getCell(colNumber);
                    cell.border = {
                        top: borderStyle,
                        left: borderStyle,
                        bottom: borderStyle,
                        right: borderStyle
                    };
                    cell.alignment = {
                        vertical: 'middle',
                        horizontal: columns[colNumber - 1].verticalAlignment ?? 'left'
                    };
                }
            }

            // Download file
            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            CommonUtils.downloadBlob(blob, fileName);
        },

        /**
         * Chuẩn hóa dữ liệu import Excel.
         */
        normalizeExcelCellData(data, columnIndex) {
            if (columnIndex >= data.length) {
                return '';
            }

            const value = data[columnIndex];
            // console.log(typeof value);

            // Dữ liệu rỗng
            if (typeof value == 'undefined') {
                return '';
            }

            if (typeof value == 'string') {
                return value.trim();
            }

            // Hyperlink
            if (typeof value == 'object') {
                const text = value.text;
                if (typeof text == 'string') {
                    return text.trim();
                }
                return ('' + text).trim();
            }

            // Số, chuyển về xâu
            return ('' + value).trim();
        }
    }
};
