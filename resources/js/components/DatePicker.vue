<template>
    <div class="input-group cursor-pointer date-picker">
        <input type="text"
               class="form-control bg-transparent border-right-0"
               :data-validation="isRequired ? 'required' : ''"
               readonly
               :placeholder="placeholder"
               ref="theInput">

        <div class="input-group-append">
            <span class="input-group-text bg-transparent border-left-0 px-0 pt-0 font-weight-700 font-size-1.25"
                    @click="clearDateFilter($event)">
                <span class="text-danger"
                        v-if="!isRequired && value">
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
        placeholder: {
            type: String,
            default: 'Chọn ngày'
        },

        isRequired: {
            type: Boolean,
            default: false
        },

        /**
         * Đối tượng moment
         */
        value: {
            type: Object,
            default: null
        },

        minDate: {
            type: Object,
            default: null
        },

        maxDate: {
            type: Object,
            default: null
        }
    },

    data() {
        return {
            options: {
                // Chỉ chọn 1 ngày
                singleDatePicker: true,
                // Hiển thị dropdown, để chọn cho nhanh (ví dụ ngày sinh)
                showDropdowns: true,
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
                }
            }
        };
    },

    mounted() {
        this.init();
        this.setDate();
    },

    watch: {
        value(newValue, oldValue) {
            this.setDate();
        }
    },

    methods: {
        /**
         * Khởi tạo.
         */
        init() {
            if (this.maxDate) {
                this.options.maxDate = this.maxDate;
            }

            if (this.minDate) {
                this.options.minDate = this.minDate;
            }

            $(this.$el)
                .daterangepicker(this.options)
                .on('apply.daterangepicker', this.applyDatePicker);
        },

        /**
         * Xử lý khi chọn thời gian.
         */
        applyDatePicker(evt, picker) {
            const date = picker.startDate;
            this.$emit('input', date);
            this.$emit('change');

            // Chờ khi giá trị đã được cập nhật ở thẻ input thì
            // tạo sự kiện 'input'
            // để thư viện Common Validation bắt được
            this.$nextTick(() => {
                const event = new Event('input', { bubbles: true });
                this.$refs.theInput.dispatchEvent(event);
            });
        },

        /**
         * Xóa thời gian.
         */
        clearDateFilter(evt) {
            if (!this.isRequired && this.value) {
                evt.stopPropagation();
                this.$emit('input', null);
                this.$emit('change');
            }
        },

        /**
         * Thiết lập ngày.
         */
        setDate() {
            const date = this.value;
            if (date) {
                $(this.$el).data('daterangepicker').setStartDate(date);
                $(this.$el).data('daterangepicker').setEndDate(date);

                const s = date.format(this.options.locale.format);
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
.date-picker {
    .form-control {
        width: 110px;
    }

    .input-group-text {
        width: 26px;
    }
}
</style>
