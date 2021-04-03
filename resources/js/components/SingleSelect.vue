<template>
    <div class="single-select dropdown">
        <div class="custom-select dropdown-toggle no-caret-right-down text-truncate"
                :class="{
                    'bg-none': showClear && selectedName
                }"
                data-toggle="dropdown">
            <span v-if="selectedName">
                {{selectedName}}
            </span>
            <span v-else class="text-muted">
                {{placeholder}}
            </span>

            <span class="clear-icon cursor-pointer font-weight-700 text-danger d-inline-block font-size-1.25 position-absolute text-center h-100"
                    title='Click để xóa'
                    v-if="showClear && selectedName"
                    @click.stop="notifyChange({})">
                &times;
            </span>
        </div>

        <!-- Dropdown -->
        <div class="dropdown-menu shadow-sm w-100">
            <!-- Tìm kiếm -->
            <div class="p-2" v-if="hasSearch && options.length > 5">
                <input type="text"
                        class="form-control"
                        v-model.trim="searchText"
                        @keydown.enter.prevent=""/>
            </div>

            <!-- Thông báo -->
            <div v-show="filterOptions.length == 0" class="text-danger p-2">
                Không tìm thấy bản ghi
            </div>

            <div class="item-list overflow-auto custom-scrollbar">
                <!-- Danh sách dữ liệu -->
                <div class="dropdown-item position-relative"
                        v-for="opt in filterOptions"
                        :key="opt.id"
                        @click="notifyChange(opt)">
                    <i class="la la-check text-success position-absolute check-icon"
                            v-if="value.id == opt.id"></i>

                    {{opt.name}}
                </div>
            </div>
        </div>
    </div>
</template>


<script>
// TODO: Tích hợp với Common Validation

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
    .item-list {
        max-height: 500px;
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
}
</style>
