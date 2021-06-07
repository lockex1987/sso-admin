<template>
    <div class="autocomplete__dropdown custom-scrollbar border shadow-sm" @scroll="handleScroll()">
        <div v-for="(e, idx) in filteredItems"
            class="autocomplete__item"
            :class="{
                    'selected': e.id == selectedItem.id,
                    'active': idx == currentIndex
                }"
            :key="e.id"
            :ref="'item' + idx"
            @click="selectWhenClick(idx)">
            <slot name="item-template"
                :item="e">
                <!-- Ảnh đại diện -->
                <img :src="e.image"
                    onerror="this.src = '/images/no-avatar.jpg'"
                    class="rounded-circle mr-2"
                    style="width: 3rem; height: 3rem;"
                    v-if="e.image" />
                {{e.name}}
            </slot>
        </div>

        <div class="text-danger p-2"
            v-show="filteredItems.length == 0">
            Không tìm thấy bản ghi
        </div>
    </div>
</template>


<script>
/**
 * Xử lý khi click bên trong / bên ngoài dropdown.
 */
document.addEventListener('click', (evt) => {
    const target = evt.target;
    const dropdown = target.closest('.autocomplete__dropdown');
    if (dropdown) {
        evt.stopPropagation();
    } else {
        // Khi click ra ngoài thì ẩn tất cả dropdown
        document.querySelectorAll('.autocomplete__dropdown').forEach((e) => {
            e.style.display = 'none';
        });
    }
});

export default {
    props: {
        // Text đang nhập
        tagModel: String,

        options: {
            type: Array,
            default: []
        },

        selectedItem: {
            type: Object,
            default: {}
        },

        filterText: {
            type: String,
            default: ''
        },

        // Có tính năng loadmore hay không
        hasLoadMore: {
            type: Boolean,
            default: false
        },

        // Dữ liệu bổ sung đang được xử lý
        isProcessing: {
            type: Boolean,
            default: false
        },

        // Có lọc các danh sách các phần tử được trả về theo từ khóa không
        noFilter: {
            type: Boolean,
            default: true
        }
    },

    data() {
        return {
            // Chỉ số của giá trị đang được chọn (ở danh sách tìm kiếm)
            currentIndex: -1,
            // chỉ số phân trang
            page: 0
        };
    },

    watch: {
        /**
         * Khi có thay đổi danh sách, reset lại thông tin.
         */
        options(val) {
            this.resetActive();
        },

        /**
         * Khi bind phần tử mới thì reset chỉ số phân trang.
         */
        tagModel(val) {
            this.page = 0;
        }
    },

    computed: {
        filteredItems() {
            if (this.noFilter) {
                return this.options;
            }
            const val = this.filterText.toLowerCase().trim();
            if (!val) {
                return this.options;
            }

            if (!this.options) {
                return this.options;
            }
            return this.options.filter(e => e.name.toLowerCase().includes(val)).length > 0
                ? this.options.filter(e => e.name.toLowerCase().includes(val))
                : this.options.filter(e => e.username.toLowerCase().includes(val));
        }
    },

    methods: {
        // Xử lý option loadmore
        handleScroll() {
            // Chỉ chạy khi có tính năng loadmore và dữ liệu đã được xử lý xong
            if (this.hasLoadMore && !this.isProcessing) {
                if ($(this.$el).scrollTop() + $(this.$el).innerHeight() >= $(this.$el)[0].scrollHeight) {
                    this.page++;
                    this.$emit('search', this.page);
                }
            }
        },

        /**
         * Hiển thị dropdown.
         */
        show() {
            this.$el.style.display = 'block';
            this.$el.scrollTop = 0;
        },

        /**
         * Ẩn dropdown.
         */
        hide() {
            this.$el.style.display = 'none';
        },

        /**
         * Di chuyển phần tử active lên trên.
         */
        moveUp() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
                this.updateActiveItem();
            }
        },

        /**
         * Di chuyển phần tử active xuống dưới.
         */
        moveDown() {
            if (this.currentIndex < this.filteredItems.length - 1) {
                this.currentIndex++;
                this.updateActiveItem();
            }
        },

        /**
         * Reset lại chỉ số chọn.
         */
        resetActive() {
            this.currentIndex = -1;
        },

        /**
         * Thiết lập phần tử đang active.
         * Chắc chắn phần tử active được nhìn thấy.
         */
        updateActiveItem() {
            const dropdown = this.$el;
            const activeItem = this.$refs['item' + this.currentIndex][0];

            const childTop = activeItem.offsetTop;
            const parentTop = dropdown.scrollTop;

            const childHeight = activeItem.clientHeight;
            const parentHeight = dropdown.clientHeight;

            const childBottom = childTop + childHeight;
            const parentBottom = parentTop + parentHeight;

            if (childTop < parentTop) {
                dropdown.scrollTop = childTop;
            } else if (parentBottom < childBottom) {
                dropdown.scrollTop = childBottom - parentHeight;
            }
        },

        /**
         * Chọn phần tử active.
         */
        selectCurrent() {
            if (this.currentIndex >= 0 &&
                    this.currentIndex < this.filteredItems.length) {
                // Hành động tương tự như được click
                this.selectWhenClick(this.currentIndex);

                return true;
            }

            return false;
        },

        /**
         * Khi click vào item thì chọn phần tử đó.
         */
        selectWhenClick(idx) {
            this.hide();
            this.$emit('change', this.filteredItems[idx]);
            this.resetActive();
        }
    }
};
</script>


<style scoped lang="scss">
.autocomplete__dropdown {
    position: absolute;
    z-index: 99;
    // Hiển thị ở phía dưới
    top: 100%;
    // Rộng bằng container
    left: 0;
    right: 0;
    max-height: 250px;
    overflow: auto;
    background-color: #fff;
    box-shadow: 0 2px 2px #999;
    display: none;

    .autocomplete__item {
        padding: 10px;
        cursor: pointer;

        &:last-child {
            border-bottom: none;
        }

        &.selected {
            background-color: #e9e9e9;

            &::after {
                content: "✓";
                color: #19a84c;
                float: right;
            }
        }

        &:hover {
            background-color: #3875d7;
            color: #ffffff;
        }

        &.active {
            background-color: #3875d7;
            color: #ffffff;
        }
    }
}
</style>
