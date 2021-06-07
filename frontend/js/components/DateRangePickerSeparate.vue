<template>
    <div class="d-flex align-items-start justify-content-between date-range-picker-separate">
        <div class="validate-container d-inline-block">
            <div class="input-group cursor-pointer"
                    ref="startDateContainer">
                <input type="text"
                        class="form-control bg-transparent border-right-0"
                        :data-validation="(isStartRequired ? 'required|' : '') + 'date'"
                        ref="startDateInput"
                        :placeholder="startPlaceholder + (isStartRequired ? ' *' : '')"
                        readonly/>

                <div class="input-group-append">
                    <span class="input-group-text bg-transparent border-left-0 px-0 pt-0 font-weight-700 font-size-1.25"
                            @click="clearStartDate($event)">
                        <span class="text-danger"
                                v-if="!isStartRequired && value.startDate">
                            &times;
                        </span>

                        <i class="la la-calendar mt-2"
                                v-else></i>
                    </span>
                </div>
            </div>
        </div>

        <i class="la la-arrow-right mt-3"></i>

        <div class="validate-container d-inline-block">
            <div class="input-group cursor-pointer"
                    ref="endDateContainer">
                <input type="text"
                        class="form-control bg-transparent border-right-0"
                        :data-validation="(isEndRequired ? 'required|' : '') + 'date'"
                        ref="endDateInput"
                        :placeholder="endPlaceholder + (isEndRequired ? ' *' : '')"
                        readonly/>

                <div class="input-group-append">
                    <span class="input-group-text bg-transparent border-left-0 px-0 pt-0 text-right font-weight-700 font-size-1.25"
                            @click="clearEndDate($event)">
                        <span class="text-danger"
                                v-if="!isEndRequired && value.endDate">
                            &times;
                        </span>

                        <i class="la la-calendar mt-2"
                                v-else></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        startPlaceholder: {
            type: String,
            default: 'Ngày bắt đầu'
        },

        endPlaceholder: {
            type: String,
            default: 'Ngày kết thúc'
        },

        isStartRequired: {
            type: Boolean,
            default: true
        },

        isEndRequired: {
            type: Boolean,
            default: true
        },

        /**
         * Đối tượng { startDate, endDate },
         * mỗi thuộc tính là đối tượng moment
         */
        value: {
            type: Object,
            default: {}
        },

        maxDaysSpan: {
            type: Number,
            default: 365 // 1 năm
        },

        minStartDate: {
            type: Object,
            default: null
        },

        maxStartDate: {
            type: Object,
            default: null
        },

        minEndDate: {
            type: Object,
            default: null
        },

        maxEndDate: {
            type: Object,
            default: null
        },

        options: {
            type: Object,
            default: null
        }
    },

    data() {
        return {
            // Cấu hình mặc định
            defaultOptions: {
                // Chỉ chọn 1 ngày
                singleDatePicker: true,
                // Hiển thị dropdown, để chọn cho nhanh (ví dụ ngày sinh)
                showDropdowns: true,
                autoUpdateInput: false,
                locale: {
                    format: 'DD/MM/YYYY',
                    separator: ' - ',
                    applyLabel: 'Áp dụng',
                    cancelLabel: 'Hủy',
                    customRangeLabel: 'Tùy chỉnh',
                    fromLabel: 'Từ',
                    toLabel: 'Đến',
                    daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', '7'],
                    monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                    firstDay: 1
                },

                // Hiển thị popup picker lên trên
                // Ở màn hình có chiều cao bé, nếu để popup phía dưới thì
                // có thể không scroll được (date-range-picker-separate ở trên modal)
                drops: 'up'
            }
        };
    },

    mounted() {
        this.init();
        this.setDate();
    },

    watch: {
        value(newValue, oldValue) {
            this.init();
            this.setDate();
        }
    },

    methods: {
        /**
         * Khởi tạo.
         */
        init() {
            this.initStartDate();
            this.initEndDate();
        },

        setDate() {
            this.setStartDate();
            this.setEndDate();
        },

        /**
         * Chọn ngày bắt đầu.
         */
        initStartDate() {
            // Xóa instance cũ
            if ($(this.$refs.startDateContainer).data('daterangepicker') != null) {
                $(this.$refs.startDateContainer).data('daterangepicker').remove();
            }

            // Khởi tạo tùy chọn
            const options = Object.assign({}, this.defaultOptions, this.options);

            // Không thể để minDate, maxDate là các giá trị null, nếu không chỉ thiết lập được tháng hiện tại
            if (this.value.endDate) {
                options.minDate = moment(this.value.endDate).subtract(this.maxDaysSpan, 'days');
                options.maxDate = this.value.endDate;
            } else {
                options.minDate = moment().subtract(this.maxDaysSpan, 'days');
                options.maxDate = moment().add(this.maxDaysSpan, 'days');
            }

            // Valiate khoảng ngày
            if (this.minStartDate) {
                if (this.minStartDate.isAfter(options.minDate)) {
                    options.minDate = this.minStartDate;
                }
            }

            if (this.maxStartDate) {
                if (this.maxStartDate.isBefore(options.maxDate)) {
                    options.maxDate = this.maxStartDate;
                }
            }

            // Khởi tạo instance
            $(this.$refs.startDateContainer)
                .daterangepicker(options)
                .on('apply.daterangepicker', this.applyStartDatePicker);
        },

        /**
         * Hàm thực hiện khi người dùng chọn ngày bắt đầu.
         */
        applyStartDatePicker(evt, picker) {
            const chosenDate = picker.startDate;
            let startMoment;

            if (chosenDate.isValid()) {
                startMoment = chosenDate;
            } else {
                startMoment = null;
            }

            this.$emit('input', {
                startDate: startMoment,
                endDate: this.value.endDate
            });
            this.$emit('change');

            // Tích hợp Common Validation
            // Chờ khi giá trị đã được cập nhật ở thẻ input thì
            // tạo sự kiện 'input'
            // để thư viện Common Validation bắt được
            this.$nextTick(() => {
                const event = new Event('input', { bubbles: true });
                this.$refs.startDateInput.dispatchEvent(event);
            });
        },

        /**
         * Chọn ngày kết thúc.
         */
        initEndDate() {
            // Xóa instance cũ
            if ($(this.$refs.endDateContainer).data('daterangepicker') != null) {
                $(this.$refs.endDateContainer).data('daterangepicker').remove();
            }

            // Khởi tạo tùy chọn
            const options = Object.assign({}, this.defaultOptions, this.options);

            // Không thể để minDate, maxDate là các giá trị null, nếu không chỉ thiết lập được tháng hiện tại
            if (this.value.startDate) {
                options.minDate = this.value.startDate;
                options.maxDate = moment(this.value.startDate).add(this.maxDaysSpan, 'days');
            } else {
                options.minDate = moment().subtract(this.maxDaysSpan, 'days');
                options.maxDate = moment().add(this.maxDaysSpan, 'days');
            }

            // Valiate khoảng ngày
            if (this.minEndDate) {
                if (this.minEndDate.isAfter(options.minDate)) {
                    options.minDate = this.minEndDate;
                }
            }

            if (this.maxEndDate) {
                if (this.maxEndDate.isBefore(options.maxDate)) {
                    options.maxDate = this.maxEndDate;
                }
            }

            $(this.$refs.endDateContainer)
                .daterangepicker(options)
                .on('apply.daterangepicker', this.applyEndDatePicker);
        },

        /**
         * Hàm thực hiện khi người dùng chọn ngày kết thúc.
         */
        applyEndDatePicker(evt, picker) {
            const chosenDate = picker.startDate;
            let endMoment;

            if (chosenDate.isValid()) {
                endMoment = chosenDate;
            } else {
                endMoment = null;
            }

            this.$emit('input', {
                startDate: this.value.startDate,
                endDate: endMoment
            });
            this.$emit('change');

            // Tích hợp Common Validation
            // Chờ khi giá trị đã được cập nhật ở thẻ input thì
            // tạo sự kiện 'input'
            // để thư viện Common Validation bắt được
            this.$nextTick(() => {
                const event = new Event('input', { bubbles: true });
                this.$refs.endDateInput.dispatchEvent(event);
            });
        },

        /**
         * Thiết lập ngày bắt đầu.
         */
        setStartDate() {
            if (this.value.startDate) {
                $(this.$refs.startDateInput).val(this.value.startDate.format(this.defaultOptions.locale.format));

                $(this.$refs.startDateContainer).data('daterangepicker').setStartDate(this.value.startDate);
                $(this.$refs.startDateContainer).data('daterangepicker').setEndDate(this.value.startDate);
            } else {
                $(this.$refs.startDateInput).val('');

                $(this.$refs.startDateContainer).data('daterangepicker').setStartDate(moment());
                $(this.$refs.startDateContainer).data('daterangepicker').setEndDate(moment());
            }
        },

        /**
         * Thiết lập ngày kết thúc.
         */
        setEndDate() {
            if (this.value.endDate) {
                $(this.$refs.endDateInput).val(this.value.endDate.format(this.defaultOptions.locale.format));

                $(this.$refs.endDateContainer).data('daterangepicker').setStartDate(this.value.endDate);
                $(this.$refs.endDateContainer).data('daterangepicker').setEndDate(this.value.endDate);
            } else {
                $(this.$refs.endDateInput).val('');

                $(this.$refs.endDateContainer).data('daterangepicker').setStartDate(moment());
                $(this.$refs.endDateContainer).data('daterangepicker').setEndDate(moment());
            }
        },

        /**
         * Xóa ngày bắt đầu.
         */
        clearStartDate(evt) {
            if (!this.isStartRequired && this.value.startDate) {
                evt.stopPropagation();
                this.$emit('input', {
                    startDate: null,
                    endDate: this.value.endDate
                });
                this.$emit('change');
            }
        },

        /**
         * Xóa ngày kết thúc.
         */
        clearEndDate(evt) {
            if (!this.isEndRequired && this.value.endDate) {
                evt.stopPropagation();
                this.$emit('input', {
                    startDate: this.value.startDate,
                    endDate: null
                });
                this.$emit('change');
            }
        }
    }
};
</script>


<style scoped lang="scss">
.date-range-picker-separate {
    .form-control {
        max-width: 150px;
    }

    .input-group-text {
        width: 26px;
    }
}
</style>
