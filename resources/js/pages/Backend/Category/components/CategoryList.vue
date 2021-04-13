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

        <div class="datatable-wrapper">
            <table class="table table-bordered"
                ref="searchResult"
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
                    <tr v-for="category in categoryList"
                        :key="category.id">
                        <td class="text-center">
                            {{category.stt}}
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
        </div>

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

            // Đối tượng datatable
            datatable: null
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
         * Khởi tạo đối tượng datatable.
         */
        initDatatable() {
            this.datatable = new Datatable({
                table: this.$refs.searchResult,
                ajax: (page, pageSize, sortColumn, sortDirection) => {
                    const params = {
                        table: this.categoryObj.table,
                        search: this.searchText,
                        page: page,
                        size: pageSize
                    };
                    return axios.get('/category/search', { params });
                },
                bindItemsCallback: (items) => {
                    this.categoryList = items;
                },
                getTotalAndData: ({ data }) => {
                    return {
                        total: data.total,
                        data: data.data
                    };
                },
                showLoading: true
            });
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
        search() {
            this.datatable.reload();
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
