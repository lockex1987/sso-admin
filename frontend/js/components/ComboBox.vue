<template>
    <div>
        <div class="combobox position-relative"
                :class="{ 'has-clear': showClear }">
            <input type="text"
                    v-model.trim="displayText"
                    ref="inputTag"
                    :placeholder="placeholder"
                    :readonly="readonly"
                    class="form-control bg-white custom-select combobox__input"
                    autocomplete='off'
                    @click.stop=""
                    @focus="showAutocompleteDropdownWhenFocus()"
                    @input="synchronizeFilterText()"
                    @blur="validateWhenBlur()"
                    @keydown="handleAutocompleteControls($event)"/>

            <span class="x cursor-pointer font-weight-700 text-danger d-inline-block font-size-1.25 position-absolute text-center h-100"
                    title='Click để xóa'
                    v-if="showClear"
                    @click="clearSelected()">
                &times;
            </span>

            <auto-complete
                    :options="options"
                    :selected-item="selectedItem"
                    :filter-text="filterText"
                    ref="autoComplete"
                    @change="processSelect($event)">
                <template v-slot:item-template="slotProps">
                    <slot name="item-template" v-bind:item="slotProps.item">
                        {{slotProps.item.name}}
                    </slot>
                </template>
            </auto-complete>
        </div>
    </div>
</template>


<script>
// Không nên dùng, dùng SingleSelect hoặc MultiSelect
export default {
    props: {
        placeholder: {
            type: String,
            default: ''
        },

        readonly: {
            type: Boolean,
            default: false
        },

        options: {
            type: Array,
            default: []
        },

        selectedItem: {
            type: Object,
            default: {}
        },

        showClear: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            displayText: '',
            filterText: ''
        };
    },

    methods: {
        processSelect(item) {
            this.displayText = item.name;
            this.filterText = '';
            this.$emit('change', item);
        },

        clearSelected() {
            this.displayText = '';
            this.filterText = '';
            this.$emit('change', {});
        },

        showAutocompleteDropdownWhenFocus() {
            this.$refs.autoComplete.resetActive();
            this.$refs.autoComplete.show();
        },

        /**
         * Khi blur thì thiết lập giá trị lại ô text.
         * Ô text có thể đang có một giá trị nào đó mà không có ở danh sách.
         * Chỉ được phép chọn trong danh sách.
         * Nếu có trong danh sách thì lấy luôn giá trị đó.
         */
        validateWhenBlur() {
            const val = this.displayText.toLowerCase().trim();
            const item = this.options.find(e => e.name.toLowerCase() == val);
            if (item) {
                this.processSelect(item);
            } else {
                this.displayText = this.selectedItem.name;
            }
        },

        /**
         * Thay đổi giá trị chọn khi người dùng nhấn phím UP hoặc DOWN.
         * Chọn khi người dùng nhấn ENTER.
         * Ẩn khi người dùng nhấn TAB.
         */
        handleAutocompleteControls(evt) {
            const keyCode = evt.keyCode;

            if (keyCode == 40) {
                // Mũi tên xuống
                this.$refs.autoComplete.moveDown();
            } else if (keyCode == 38) {
                // Mũi tên lên
                this.$refs.autoComplete.moveUp();
            } else if (keyCode == 13) {
                // Nhấn ENTER
                // Không submit form
                evt.preventDefault();

                // Đang có một giá trị được active
                if (this.$refs.autoComplete.selectCurrent()) {
                    this.$refs.inputTag.blur();
                }
            } else if (keyCode == 9) {
                // Nhấn TAB thì ẩn
                this.$refs.autoComplete.hide();
            }
        },

        synchronizeFilterText() {
            this.filterText = this.displayText;
        }
    }
};
</script>


<style scoped lang="scss">
.combobox {
    $iconWidth: 25px;

    &.has-clear {
        input {
            padding-right: $iconWidth;
        }
    }

    .x {
        width: $iconWidth;
        right: 1.5rem;
        top: 0;
        padding-top: 2px;
    }
}
</style>
