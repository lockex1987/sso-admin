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
                v-show="appList.length > 0">
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
                        <th class="text-center">
                            Login redirect
                        </th>
                        <th class="text-center">
                            Logout redirect
                        </th>
                        <th class="text-center"
                            style="width: 215px;">
                            Thao tác
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="app in appList"
                        :key="app.id">
                        <td class="text-center">
                            {{app.stt}}
                        </td>
                        <td>
                            {{app.code}}
                        </td>
                        <td>
                            {{app.name}}
                        </td>
                        <td>
                            {{app.login_redirect}}
                        </td>
                        <td>
                            {{app.logout_redirect}}
                        </td>
                        <td class="text-center">
                            <i class="cursor-pointer la la-lg la-pencil text-info mr-2"
                                title="Cập nhật"
                                @click="openUpdateForm(app)"></i>

                            <i class="cursor-pointer la la-lg la-trash text-danger mr-2"
                                title="Xóa"
                                @click="deleteRecord(app)"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <app-form ref="appForm"
            @search-again="search()" />
    </div>
</template>


<script>
import AppForm from './AppForm.vue';


export default {
    components: {
        AppForm
    },

    data() {
        return {
            // Danh sách kết quả tìm kiếm
            appList: [],

            // Text tìm kiếm
            searchText: '',

            // Đối tượng datatable
            datatable: null
        };
    },

    mounted() {
        this.initDatatable();
    },

    methods: {
        /**
         * Khởi tạo đối tượng datatable.
         */
        initDatatable() {
            this.datatable = new Datatable({
                table: this.$refs.searchResult,
                ajax: (page, pageSize, sortColumn, sortDirection) => {
                    const params = {
                        search: this.searchText,
                        page: page,
                        size: pageSize
                    };
                    return axios.get('/app/search', { params });
                },
                bindItemsCallback: (items) => {
                    this.appList = items;
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
            this.$refs.appForm.openCreateForm();
        },

        /**
         * Bật form cập nhật.
         */
        openUpdateForm(app) {
            this.$refs.appForm.openUpdateForm(app);
        },

        /**
         * Xóa bản ghi.
         */
        deleteRecord(app) {
            noti.confirm('Bạn chắc chắn muốn xoá bản ghi <b>' + CommonUtils.escapeHtml(app.name) + '</b> hay không?', async () => {
                const params = {
                    id: app.id
                };
                const { data } = await axios.delete('/app/destroy', { data: params });
                if (data.code == 0) {
                    noti.success('Xóa thành công');
                    this.search();
                }
            });
        }
    }
};
</script>
