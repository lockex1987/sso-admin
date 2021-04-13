<template>
    <div class="combo-box-page">
        <the-breadcrumb :paths="['Component demo', 'Tags Input']" />

        <div class="mb-3">
            <new-tags-input :tags="tags"
                placeholder="Chọn người"
                :min-length="1"
                :max-length="10"
                :max-tags="5"
                :custom-add-validate-function="checkBeforeAdd"
                :auto-complete-items="autoCompleteItems"
                :add-only-from-auto-complete="true"
                @add="tagAddedCallback($event)" />
        </div>

        <div class="mb-3">
            <button @click="clearData()"
                class="btn btn-danger">
                Clear
            </button>
        </div>

        <pre class="mb-3">{{tags}}</pre>
    </div>
</template>


<script>
export default {
    data() {
        return {
            tags: [],
            autoCompleteItems: []
        };
    },

    mounted() {
        // Cập nhật danh sách
        this.autoCompleteItems = [
            { id: 'nvh', name: 'Nguyễn Văn Huyên' },
            { id: 'nat', name: 'Nguyễn Anh Tuấn' },
            { id: 'cttd', name: 'Cao Thị Thùy Dương' }
        ];
    },

    methods: {
        clearData() {
            this.tags = [];
        },

        tagAddedCallback(obj) {
            console.log('Vừa thêm tag ' + JSON.stringify(obj));
        },

        checkBeforeAdd(text) {
            return '';
            /*
            console.log(text);
            return 'Không thể thêm mới';
            */
        }
    }
};
</script>


<style lang="scss">
.combo-box-page {
    .autocomplete__item .avatar {
        width: 16px;
        height: 16px;
        object-fit: cover;
    }
}
</style>
