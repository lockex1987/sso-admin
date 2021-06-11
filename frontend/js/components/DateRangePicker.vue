<template>
    <div class="input-group cursor-pointer date-range-picker">
        <input type="text"
                class="form-control bg-transparent border-right-0"
                readonly
                :placeholder="placeholder">

        <div class="input-group-append">
            <span class="input-group-text bg-transparent border-left-0 px-0 pt-0 font-weight-700 font-size-1.25"
                    @click="clearDateRangeFilter($event)">
                <span class="text-danger"
                        v-if="!isRequired && value.startDate">
                    &times;
                </span>

                <i class="la la-calendar mt-2"
                        v-else></i>
            </span>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        /**
         * Đối tượng { startDate, endDate },
         * mỗi thuộc tính là đối tượng moment
         */
        value: {
            type: Object,
            default: {}
        },

        config: {
            type: Object,
            default: null
        },

        placeholder: {
            type: String,
            default: 'Tất cả thời gian'
        },

        isRequired: {
            type: Boolean,
            default: true
        }
    },

    data() {
        const currentDate = moment();
        // let endOfWeek = moment().endOf('isoWeek');
        // let endOfMonth = moment().endOf('month');
        return {
            options: {
                locale: {
                    format: 'DD/MM/YYYY',
                    separator: ' - ',
                    applyLabel: 'Áp dụng',
                    cancelLabel: 'Hủy',
                    customRangeLabel: 'Tùy chỉnh',
                    fromLabel: 'Từ',
                    toLabel: 'Đến',
                    daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
                    monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                    firstDay: 1
                },
                ranges: {
                    'Hôm nay': [moment(), moment()],
                    'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 ngày qua': [moment().subtract(6, 'days'), moment()],
                    '30 ngày qua': [moment().subtract(29, 'days'), moment()],
                    'Tuần này': [moment().startOf('isoWeek'), currentDate], // endOfWeek
                    'Tuần trước': [moment().subtract(1, 'week').startOf('isoWeek'), moment().subtract(1, 'week').endOf('isoWeek')],
                    'Tháng này': [moment().startOf('month'), currentDate], // endOfMonth
                    'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                maxDate: currentDate,
                // Giới hạn 1 năm
                maxSpan: {
                    days: 365
                }
            }
        };
    },

    mounted() {
        this.init();

        if (this.value.startDate) {
            this.setDateRange();
        }
    },

    watch: {
        value() {
            this.setDateRange();
        }
    },

    methods: {
        /**
         * Khởi tạo.
         */
        init() {
            Object.assign(this.options, this.config);

            $(this.$el)
                .daterangepicker(this.options)
                .on('apply.daterangepicker', this.applyDateRangePicker);
        },

        /**
         * Xử lý khi chọn khoảng thời gian.
         */
        applyDateRangePicker(evt, picker) {
            this.$emit('input', {
                startDate: picker.startDate,
                endDate: picker.endDate
            });
            this.$emit('change');
        },

        /**
         * Xóa chọn khoảng thời gian.
         */
        clearDateRangeFilter(evt) {
            if (!this.isRequired && this.value.startDate) {
                evt.stopPropagation();
                this.$emit('input', {
                    startDate: null,
                    endDate: null
                });
                this.$emit('change');
            }
        },

        /**
         * Thiết lập ngày bắt đầu, ngày kết thúc.
         */
        setDateRange() {
            const obj = this.value;
            if (obj.startDate) {
                const start = obj.startDate;
                const end = obj.endDate;

                $(this.$el).data('daterangepicker').setStartDate(start);
                $(this.$el).data('daterangepicker').setEndDate(end);

                const s = start.format(this.options.locale.format)
                        + this.options.locale.separator
                        + end.format(this.options.locale.format);
                $(this.$el).find('input').val(s);
            } else {
                $(this.$el).data('daterangepicker').setStartDate(moment());
                $(this.$el).data('daterangepicker').setEndDate(moment());

                $(this.$el).find('input').val('');
            }
        }
    }
};
</script>


<style scoped lang="scss">
.date-range-picker {
    .form-control {
        width: 205px;
    }

    .input-group-text {
        width: 26px;
    }
}
</style>
