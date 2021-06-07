<template>
    <div class="single-select dropdown position-relative">
        <div class="d-flex align-items-center input-wrapper position-relative"
                data-toggle="dropdown"
                data-boundary="window">
            <!-- Ảnh đại diện -->
            <img :src="value.image"
                    onerror="this.src = '/images/no-avatar.jpg'"
                    class="rounded-circle ml-2"
                    style="width: 1.5rem; height: 1.5rem"
                    v-if="value.image"/>

            <input type="text"
                    readonly="true"
                    class="custom-select border-0 dropdown-toggle no-caret-right-down text-truncate"
                    :class="{
                        'bg-none': showClear && selectedName
                    }"
                    :placeholder="placeholder"
                    :value="selectedName"
                    :data-validation="showClear ? '' : 'required'"
                    ref="theInput"/>

            <!-- Nút xóa -->
            <span class="clear-icon cursor-pointer font-weight-700 text-danger d-inline-block font-size-1.25 position-absolute text-center h-100"
                    title='Click để xóa'
                    v-if="showClear && selectedName"
                    @click.stop="resetSearchText(); notifyChange({})">
                &times;
            </span>
        </div>

        <!-- Dropdown -->
        <div class="dropdown-menu shadow-sm">
            <!-- Tìm kiếm -->
            <div class="p-2"
                    v-if="hasSearch">
                <input type="text"
                        class="form-control"
                        v-model.trim="searchText"
                        @keydown.enter.prevent=""
                        @input="$emit('input-search', searchText);"/>
            </div>

            <!-- Thông báo -->
            <div v-show="filterOptions && filterOptions.length == 0"
                    class="text-danger p-2">
                Không tìm thấy bản ghi
            </div>

            <div class="item-list overflow-auto custom-scrollbar">
                <!-- Danh sách dữ liệu -->
                <div class="dropdown-item position-relative"
                        :class="[opt.class]"
                        v-for="opt in filterOptions"
                        :key="opt.id"
                        @click="notifyChange(opt)">
                    <i class="la la-check text-success position-absolute check-icon"
                            v-if="value.id == opt.id"></i>

                    <!-- Ảnh đại diện -->
                    <img :src="opt.image"
                            onerror="this.src = '/images/no-avatar.jpg'"
                            class="rounded-circle mr-2"
                            style="width: 2rem; height: 2rem"
                            v-if="opt.image"/>

                    {{opt.name}}
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        // Danh sách tùy chọn
        options: {
            type: Array,
            default: []
        },

        // Placeholder khi chưa có giá trị nào
        placeholder: {
            type: String,
            default: ''
        },

        // Phải là kiểu object hoặc array thì khi thay đổi ở component thì component cha mới nhận được giá trị
        value: {
            type: Object,
            default: {}
        },

        // Có ô tìm kiếm hay không
        hasSearch: {
            type: Boolean,
            default: false
        },

        // Có icon xóa không
        showClear: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            // Từ khóa tìm kiếm
            searchText: ''
        };
    },

    computed: {
        /**
         * Tên giá trị được chọn.
         * Có hàm này, vì nhiều khi value chỉ có ID.
         */
        selectedName() {
            if (this.value && this.value.name) {
                return this.value.name;
            }

            if (!this.options) {
                return '';
            }

            const obj = this.options.find(opt => opt.id == this.value.id);
            return obj ? obj.name : '';
        },

        /**
         * Danh sách dữ liệu mà đã lọc.
         */
        filterOptions() {
            if (!this.searchText) {
                return this.options;
            }

            if (!this.options) {
                return this.options;
            }

            const text = this.searchText.toLowerCase();
            return this.options.filter(opt => opt.name.toLowerCase().includes(text));
        }
    },

    methods: {
        /**
         * Thông báo thay đổi.
         */
        notifyChange(opt) {
            this.$emit('input', opt);
            this.$emit('change', opt);

            // Chờ khi giá trị đã được cập nhật ở thẻ input thì
            // tạo sự kiện 'input'
            // để thư viện Common Validation bắt được
            this.$nextTick(() => {
                const event = new Event('input', { bubbles: true });
                this.$refs.theInput.dispatchEvent(event);
            });
        },

        /**
         * Component cha gọi qua phương thức này.
         */
        resetSearchText() {
            this.searchText = '';
        }
    }
};
</script>


<style scoped lang="scss">
.single-select {
    // Border giống như .custom-select
    .input-wrapper {
        border: 1px solid #ced4da;
        border-radius: .25rem;
    }

    .item-list {
        max-height: 250px;
    }

    .dropdown-item {
        padding-left: 2rem;
    }

    .check-icon {
        left: 0.5rem;
        top: 0.5rem;
    }

    .clear-icon {
        width: 24px;
        right: 5px;
        top: 0;
        padding-top: 2px;
    }

    .dropdown-menu {
        min-width: 100%;
        width: auto;
    }
}
</style>
