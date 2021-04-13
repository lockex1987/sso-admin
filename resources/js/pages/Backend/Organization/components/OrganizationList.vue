<template>
    <div>
        <top-header :paths="['Quản trị', 'Tổ chức']">
            <template v-slot:buttons>
                <button class="btn btn-primary btn-ripple mr-2"
                    type="button"
                    @click="openCreateForm()">
                    Thêm mới
                </button>
            </template>
        </top-header>

        <div class="row">
            <div class="col-lg-3">
                <input type="text"
                    class="form-control mb-3"
                    v-model.trim="searchText"
                    placeholder="Từ khóa"
                    @input="debouncedSearch()">

                <organization-search-tree @selected="selectNodeOfLeftTree($event)"
                    :selected-org="selectedOrg"
                    :all-list="allListAndAllOption" />
            </div>

            <div class="col-lg-9">
                <div class="datatable-wrapper">
                    <table class="table table-bordered"
                        ref="searchResult"
                        v-show="orgList.length > 0">
                        <thead>
                            <tr>
                                <th class="text-center"
                                    style="width: 50px">
                                    STT
                                </th>
                                <th class="text-center">
                                    Tên tổ chức
                                </th>
                                <th class="text-center">
                                    Mô tả
                                </th>
                                <th class="text-center"
                                    style="width: 215px;">
                                    Thao tác
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="org in orgList"
                                :key="org.id">
                                <td class="text-center">
                                    {{org.stt}}
                                </td>
                                <td>
                                    {{org.name}}
                                </td>
                                <td class="text-break">
                                    {{org.description}}
                                </td>
                                <td class="text-center">
                                    <i class="cursor-pointer la la-lg la-pencil text-info"
                                        title="Cập nhật"
                                        @click="openUpdateForm(org)"></i>

                                    <i class="cursor-pointer la la-lg la-trash text-danger ml-2"
                                        title="Xóa"
                                        @click="deleteRecord(org)"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <organization-form ref="frm"
            @stored="storeCallback()"
            :org-list="allList" />
    </div>
</template>


<script>
import OrganizationForm from './OrganizationForm.vue';
import OrganizationSearchTree from './OrganizationSearchTree.vue';

export default {
    components: {
        OrganizationForm,
        OrganizationSearchTree
    },

    data() {
        return {
            // Danh sách kết quả tìm kiếm
            orgList: [],

            // Text tìm kiếm
            searchText: '',

            // Đối tượng datatable
            datatable: null,

            // Đơn vị đang được chọn
            selectedOrg: {},

            // Danh sách tất cả tổ chức
            allList: []
        };
    },

    computed: {
        allListAndAllOption() {
            return [
                {
                    id: null,
                    name: 'TẤT CẢ TỔ CHỨC',
                    parentId: null,
                    path: ''
                },
                ...this.allList
            ];
        }
    },

    mounted() {
        this.initDatatable();
        this.getAllList();
    },

    methods: {
        /**
         * Lấy tất cả dữ liệu đơn vị.
         */
        async getAllList() {
            const { data } = await axios.get('/organization/all');

            // Chuẩn hóa cấu trúc dữ liệu của cây
            this.allList = data.map(e => {
                return {
                    id: e.id,
                    name: e.name,
                    parentId: e.parent_id,
                    path: e.path
                };
            });
        },

        /**
         * Khởi tạo đối tượng datatable.
         */
        initDatatable() {
            this.datatable = new Datatable({
                table: this.$refs.searchResult,
                ajax: (page, size, sortColumn, sortDirection) => {
                    const params = {
                        search: this.searchText,
                        parentId: this.selectedOrg.id,
                        page: page,
                        size: size
                    };
                    return axios.get('/organization/search', { params });
                },
                bindItemsCallback: (items) => {
                    this.orgList = items;
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
            this.$refs.frm.openCreateForm();
        },

        /**
         * Bật form cập nhật.
         */
        openUpdateForm(org) {
            this.$refs.frm.openUpdateForm(org);
        },

        /**
         * Xóa bản ghi.
         */
        deleteRecord(org) {
            noti.confirm('Bạn chắc chắn muốn xoá bản ghi <b>' + CommonUtils.escapeHtml(org.name) + '</b> hay không?', async () => {
                // Gọi lên server
                const params = {
                    id: org.id
                };
                const { data: resp } = await axios.delete('/organization/destroy', { data: params });
                if (resp.code == 0) {
                    // Thông báo xóa thành công
                    noti.success('Xóa thành công');

                    this.storeCallback();
                }
            });
        },

        /**
         * Chọn một đơn vị ở cây bên trái.
         */
        selectNodeOfLeftTree(org) {
            this.selectedOrg = org;
            this.search();
        },

        /**
         * Gọi khi thêm / sửa / xóa thành công.
         */
        storeCallback() {
            // Load lại cây bên trái
            // Load lại chọn cha ở form
            this.selectedOrg = {};
            this.getAllList();

            // Tìm kiếm lại
            this.search();
        }
    }
};
</script>


<style lang="scss" scoped>
.left-tree {
    width: 200px;
}
</style>
