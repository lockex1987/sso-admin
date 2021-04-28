<template>
    <div>
        <div class="form-inline">
            <input type="text"
                class="form-control mb-2 mr-sm-2"
                v-model.trim="searchText"
                placeholder="Từ khóa"
                @input="debouncedSearch()">

            <button class="btn btn-primary btn-ripple mb-2 ml-auto"
                type="button"
                @click="openCreateForm()">
                Thêm mới
            </button>
        </div>

        <table class="table table-bordered"
            v-show="categoryList.length > 0">
            <thead>
                <tr>
                    <th class="text-center"
                        style="width: 50px">
                        #
                    </th>
                    <th class="text-center">
                        Mã
                    </th>
                    <th class="text-center">
                        Tên
                    </th>
                    <th class="text-center"
                        style="width: 215px;">
                        Thao tác
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(category, i) in categoryList"
                    :key="category.id">
                    <td class="text-center">
                        {{pagi.from + i}}
                    </td>
                    <td>
                        {{category.code}}
                    </td>
                    <td>
                        {{category.name}}
                    </td>
                    <td class="text-center">
                        <i class="cursor-pointer la la-lg la-pencil text-info mr-2"
                            title="Cập nhật"
                            @click="openUpdateForm(category)"></i>

                        <i class="cursor-pointer la la-lg la-trash text-danger mr-2"
                            title="Xóa"
                            @click="deleteRecord(category)"></i>
                    </td>
                </tr>
            </tbody>
        </table>

        <pagi @change="search"
            v-model="pagi"></pagi>

        <category-form ref="categoryForm"
            :category-obj="categoryObj"
            @stored="search()" />
    </div>
</template>


<script>
import CategoryForm from './CategoryForm.vue';

export default {
    components: {
        CategoryForm
    },

    props: {
        categoryObj: Object
    },

    data() {
        return {
            // Danh sách kết quả tìm kiếm
            categoryList: [],

            // Text tìm kiếm
            searchText: '',

            // Đối tượng Pagi
            pagi: {}
        };
    },

    mounted() {

    },

    methods: {
        /**
         * Rest lại form tìm kiếm.
         */
        resetForm() {
            this.searchText = '';
        },

        /**
         * Lọc theo từ khóa.
         */
        debouncedSearch: CommonUtils.debounce(
            function () {
                this.search();
            },
            500
        ),

        /**
         * Tìm kiếm.
         */
        async search(page = 1) {
            const params = {
                table: this.categoryObj.table,
                search: this.searchText,
                page: page,
                size: 10
            };
            const { data } = await axios.get('/category/search', { params });
            this.pagi = data;
            this.categoryList = data.data;
        },

        /**
         * Bật form thêm mới.
         */
        openCreateForm() {
            this.$refs.categoryForm.openCreateForm();
        },

        /**
         * Bật form cập nhật.
         */
        openUpdateForm(category) {
            this.$refs.categoryForm.openUpdateForm(category);
        },

        /**
         * Xóa bản ghi.
         */
        deleteRecord(category) {
            const params = {
                table: this.categoryObj.table,
                id: category.id
            };
            noti.confirm('Bạn chắc chắn muốn xoá bản ghi <b>' + CommonUtils.escapeHtml(category.code) + '</b> hay không?', async () => {
                const { data } = await axios.delete('/category/destroy', { data: params });
                if (data.code == 0) {
                    noti.success('Xóa thành công');
                    this.search();
                } else if (data.code == 2) {
                    noti.error(data.message);
                }
            });
        }
    }
};
</script>
