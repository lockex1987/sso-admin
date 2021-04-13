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
                v-show="roleList.length > 0">
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
                    <tr v-for="role in roleList"
                        :key="role.id">
                        <td class="text-center">
                            {{role.stt}}
                        </td>
                        <td>
                            {{role.code}}
                        </td>
                        <td>
                            {{role.name}}
                        </td>
                        <td class="text-center">
                            <i class="cursor-pointer la la-lg la-pencil text-info mr-2"
                                title="Cập nhật"
                                @click="openUpdateForm(role)"></i>

                            <i class="cursor-pointer la la-lg la-trash text-danger mr-2"
                                title="Xóa"
                                @click="deleteRecord(role)"></i>

                            <i class="cursor-pointer la la-lg la-shield-alt text-warning mr-2"
                                title="Quyền của vai trò"
                                @click="openPermissionForm(role)"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <role-form ref="roleForm"
            @search-again="search()" />

        <role-permission-form ref="rolePermissionForm" />
    </div>
</template>


<script>
import RoleForm from './RoleForm.vue';
import RolePermissionForm from './RolePermissionForm.vue';


export default {
    components: {
        RoleForm,
        RolePermissionForm
    },

    data() {
        return {
            // Danh sách kết quả tìm kiếm
            roleList: [],

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
                        page: page - 1,
                        size: pageSize
                    };
                    return axios.get('/role/search', { params });
                },
                bindItemsCallback: (items) => {
                    this.roleList = items;
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
            this.$refs.roleForm.openCreateForm();
        },

        /**
         * Bật form cập nhật.
         */
        openUpdateForm(role) {
            this.$refs.roleForm.openUpdateForm(role);
        },

        /**
         * Xóa bản ghi.
         */
        deleteRecord(role) {
            noti.confirm('Bạn chắc chắn muốn xoá bản ghi <b>' + CommonUtils.escapeHtml(role.code) + '</b> hay không?', async () => {
                const params = {
                    id: role.id
                };
                const { data } = await axios.delete('/role/destroy', { data: params });
                if (data.code == 0) {
                    noti.success('Xóa thành công');
                    this.search();
                }
            });
        },

        /**
         * Mở popup gán quyền.
         */
        openPermissionForm(role) {
            this.$refs.rolePermissionForm.openUpdateForm(role);
        }
    }
};
</script>
