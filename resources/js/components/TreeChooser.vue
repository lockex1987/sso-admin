<template>
    <div class="tree-chooser dropdown">
        <input class="custom-select bg-transparent dropdown-toggle no-caret-right-down text-truncate"
                :class="{
                    'bg-none': showClear && selectedName
                }"
                data-toggle="dropdown"
                :data-validation="showClear ? '' : 'required'"
                ref="dropdownToggle"
                :placeholder="placeholder"
                :value="selectedName"/>

        <span class="clear-icon cursor-pointer font-weight-700 text-danger d-inline-block font-size-1.25 position-absolute text-center h-100"
                title='Click để xóa'
                v-if="showClear && selectedName"
                @click.stop="selectItem({})">
            &times;
        </span>

        <div class="dropdown-menu shadow-sm w-100 p-2"
                @click.stop="">
            <div class="mb-2"
                    v-show="showSearch">
                <input type="text"
                        class="form-control"
                        v-model.trim="searchText"
                        placeholder="Từ khóa"
                        @keydown.enter.prevent=""/>
            </div>

            <div class="custom-scrollbar overflow-auto item-list">
                <!-- Cây tổ chức -->
                <div v-show="!searchText">
                    <div class="tree tree--icon tree--selectable">
                        <tree :data="excludeIgnoreList"
                                :selected-node="value"
                                @click="selectItem($event)" />
                    </div>
                </div>

                <!-- Danh sách tổ chức (dạng phẳng) -->
                <div v-show="searchText">
                    <div v-for="e in filteredSearchList"
                            :key="e.id"
                            class="dropdown-item cursor-pointer"
                            :class="{ 'dropdown-item--selected': e.id == value.id }"
                            @click="selectItem(e)">
                        {{e.name}}
                    </div>

                    <div v-show="filteredSearchList.length == 0" class="text-danger">
                        Không tìm thấy bản ghi
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        // Tổ chức được chọn
        value: {
            type: Object
        },

        // Danh sách ID cần loại bỏ
        ignoredId: {},

        // Placeholder khi không có dữ liệu
        placeholder: {
            type: String,
            default: ''
        },

        // Danh sách tất cả tổ chức
        options: {
            type: Array
        },

        // Có icon xóa không
        showClear: {
            type: Boolean,
            default: false
        },

        // Có hiển thị ô tìm kiếm không
        showSearch: {
            type: Boolean,
            default: true
        }
    },

    data() {
        return {
            // Text tìm kiếm
            searchText: ''
        };
    },

    computed: {
        /**
         * Tên giá trị được chọn.
         * Có hàm này, vì nhiều khi value chỉ có ID.
         */
        selectedName() {
            if (!this.options) {
                return '';
            }
            const obj = this.options.find(opt => opt.id == this.value.id);
            return obj ? obj.name : '';
        },

        /**
         * Danh sách mà đã bỏ những tổ chức cần loại bỏ.
         */
        excludeIgnoreList() {
            if (!this.options) {
                return [];
            }

            // Bỏ node hiện tại khỏi cây chuyên mục cha
            // Hạn chế luôn lỗi chọn cha là chính nó hoặc con cháu của nó
            if (this.ignoredId === null || this.ignoredId === undefined) {
                return this.options;
            }

            return this.options.filter(e => {
                return e.id != this.ignoredId;
                // return !e.path.includes('/' + this.ignoredId + '/');
            });
        },

        /**
         * Lọc tổ chức.
         */
        filteredSearchList() {
            const query = this.searchText.toLowerCase();
            return this.excludeIgnoreList.filter(e => e.name.toLowerCase().includes(query));
        }
    },

    methods: {
        closeDropdown() {
            // Dùng toggle vì nó có điều chỉnh vị trí khi màn hình bé (hiển thị phía trên hoặc phía dưới)
            if ($(this.$refs.dropdownToggle).parent().hasClass('show')) {
                $(this.$refs.dropdownToggle).dropdown('toggle');
            }

            this.searchText = '';
        },

        /**
         * Chọn một tổ chức nào đó.
         */
        selectItem(item) {
            this.closeDropdown();

            this.$emit('input', item);
            this.$emit('change');

            // Chờ khi giá trị đã được cập nhật ở thẻ input thì
            // tạo sự kiện 'input'
            // để thư viện Common Validation bắt được
            this.$nextTick(() => {
                const event = new Event('input', { bubbles: true });
                this.$refs.dropdownToggle.dispatchEvent(event);
            });
        }
    }
};
</script>


<style scoped lang="scss">
.tree-chooser {
    .item-list {
        max-height: 500px;
    }

    .dropdown-item {
        padding: 2px 5px;
    }

    .dropdown-item--selected {
        background-color: yellow;
    }

    .clear-icon {
        width: 24px;
        right: 5px;
        top: 0;
        padding-top: 2px;
    }
}
</style>
