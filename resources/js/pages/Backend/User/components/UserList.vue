<template>
    <div>
        <div class="form-inline mt-5 mb-3">
            <input type="text"
                    class="form-control"
                    v-model.trim="searchText"
                    placeholder="Từ khóa"
                    @input="debouncedSearch()">

            <button class="btn btn-primary btn-ripple ml-auto"
                    type="button"
                    @click="openCreateForm()">
                Thêm mới
            </button>

            <button type="button"
                    class="btn btn-secondary ml-2"
                    @click="exportExcel()">
                Export
            </button>
        </div>

        <div class="datatable-wrapper">
            <table class="table table-bordered"
                    ref="searchResult"
                    v-show="userList.length > 0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px">
                            #
                        </th>
                        <th class="text-center">
                            Avatar
                        </th>
                        <th class="text-center">
                            Tên đăng nhập
                        </th>
                        <th class="text-center">
                            Tên hiển thị
                        </th>
                        <th class="text-center">
                            Email
                        </th>
                        <th class="text-center" style="width: 110px;">
                            Trạng thái
                        </th>
                        <th class="text-center" style="width: 215px;">
                            Thao tác
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="user in userList" :key="user.id">
                        <td class="text-center">
                            {{user.stt}}
                        </td>
                        <td class="text-center">
                            <img :src="'http://sso-passport.cttd.tk' + '/storage/avatars/' + user.avatar"
                                    class="avatar object-fit-cover rounded"
                                    v-if="user.avatar"
                                    onerror="this.src = '/images/user-avatar.png'"/>
                            <img src="/images/user-avatar.png"
                                    class="avatar object-fit-cover rounded"
                                    v-else/>
                        </td>
                        <td>
                            {{user.username}}
                        </td>
                        <td>
                            {{user.full_name}}
                        </td>
                        <td>
                            {{user.email}}
                        </td>
                        <td>
                            <span v-if="user.is_active == 1" class="text-success">
                                Active
                            </span>
                            <span v-else class="text-danger">
                                Lock
                            </span>
                        </td>
                        <td class="text-center text-nowrap">
                            <i class="cursor-pointer la la-lg la-eye text-success"
                                    title="Chuyển chế độ view"
                                    @click="viewAs(user)"
                                    v-if="user.id != currentUser.id && permissions.includes('user.view-as')"></i>

                            <i class="cursor-pointer la la-lg la-pencil text-info ml-2"
                                    title="Cập nhật"
                                    @click="openUpdateForm(user)"></i>

                            <i class="cursor-pointer la la-lg la-trash text-danger ml-2"
                                    title="Xóa"
                                    @click="deleteRecord(user)"
                                    v-if="user.id != currentUser.id"></i>

                            <span v-if="user.id != currentUser.id">
                                <i class="cursor-pointer la la-lg la-lock text-warning ml-2"
                                        title="Khóa lại"
                                        @click="lockUser(user)"
                                        v-if="user.is_active == 1"></i>

                                <i class="cursor-pointer la la-lg la-unlock text-warning ml-2"
                                        title="Mở khóa"
                                        @click="unlockUser(user)"
                                        v-else></i>
                            </span>

                            <i class="cursor-pointer la la-lg la-users text-primary ml-2"
                                    title="Gán vai trò"
                                    @click="openUpdateUserRoleForm(user)"></i>

                            <i class="cursor-pointer lab la-lg la-app-store text-secondary ml-2"
                                    title="Gán ứng dụng"
                                    @click="openUpdateUserAppForm(user)"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <user-form
                ref="userForm"
                @search-again="search()"/>

        <user-role-form
                ref="userRoleForm"/>

        <user-app-form
                ref="userAppForm"/>
    </div>
</template>


<script>
import UserForm from './UserForm.vue';
import UserRoleForm from './UserRoleForm.vue';
import UserAppForm from './UserAppForm.vue';


export default {
    components: {
        UserForm,
        UserRoleForm,
        UserAppForm
    },

    computed: {
        ...Vuex.mapState({
            currentUser: state => state.auth.user,
            permissions: state => state.auth.permissions
        })
    },

    data() {
        return {
            // Danh sách kết quả tìm kiếm
            userList: [],

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
         * Lấy các tham số khi gọi API.
         * Dùng chung ở tìm kiếm và xuất file.
         */
        getParams() {
            return {
                search: this.searchText
            };
        },

        /**
         * Khởi tạo đối tượng datatable.
         */
        initDatatable() {
            this.datatable = new Datatable({
                table: this.$refs.searchResult,
                ajax: (page, size, sortColumn, sortDirection) => {
                    const params = {
                        ...this.getParams(),
                        page: page,
                        size: size
                    };
                    return axios.get('/user/search', { params });
                },
                bindItemsCallback: (items) => {
                    this.userList = items;
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
            this.$refs.userForm.openCreateForm();
        },

        /**
         * Bật form cập nhật.
         */
        openUpdateForm(user) {
            this.$refs.userForm.openUpdateForm(user);
        },

        /**
         * Xóa bản ghi.
         */
        deleteRecord(user) {
            noti.confirm('Bạn chắc chắn muốn xoá tài khoản <b>' + CommonUtils.escapeHtml(user.username) + '</b> hay không?', async () => {
                const params = {
                    id: user.id
                };
                const { data } = await axios.delete('/user/destroy', { data: params });
                if (data.code == 0) {
                    noti.success('Xóa thành công');
                    this.search();
                } else {
                    noti.error(data.message);
                }
            });
        },

        /**
         * Khóa người dùng.
         */
        lockUser(user) {
            noti.confirm(`Bạn chắc chắn muốn khoá tài khoản <b>${CommonUtils.escapeHtml(user.username)}</b> hay không?`, async () => {
                const params = {
                    id: user.id,
                    isActive: null
                };
                const { data } = await axios.post('/user/update-is-active', params);
                if (data.code == 0) {
                    noti.success('Khóa thành công');
                    this.search();
                } else {
                    noti.error(data.message);
                }
            });
        },

        /**
         * Mở khóa người dùng.
         */
        unlockUser(user) {
            noti.confirm(`Bạn chắc chắn muốn mở khoá tài khoản <b>${CommonUtils.escapeHtml(user.username)}</b> hay không?`, async () => {
                const params = {
                    id: user.id,
                    isActive: 1
                };
                const { data } = await axios.post('/user/update-is-active', params);
                if (data.code == 0) {
                    noti.success('Mở khóa thành công');
                    this.search();
                } else {
                    noti.error(data.message);
                }
            });
        },

        /**
         * Chuyển chế độ view.
         */
        viewAs(user) {
            noti.confirm('Bạn có muốn chuyển sang chế độ view của tài khoản <b>' + CommonUtils.escapeHtml(user.username) + '</b>?', async () => {
                const params = {
                    id: user.id
                };
                const { data } = await axios.post('/user/view-as', params);
                if (data.code == 0) {
                    window.location = '/';
                } else {
                    // noti.error(data.message);
                }
            });
        },

        /**
         * Mở form gán vai trò.
         */
        openUpdateUserRoleForm(user) {
            this.$refs.userRoleForm.openUpdateForm(user);
        },

        /**
         * Mở form gán ứng dụng.
         */
        openUpdateUserAppForm(user) {
            this.$refs.userAppForm.openUpdateForm(user);
        },

        /**
         * Xuất dữ liệu Excel.
         */
        async exportExcel() {
            // Lấy dữ liệu từ server
            const params = this.getParams();
            const { data } = await axios.get('/user/search', { params });
            const list = data;
            if (list.length == 0) {
                noti.warning('Không có dữ liệu');
                return;
            }

            // Chuẩn hóa lại dữ liệu
            list.forEach(user => {
                user.statusName = user.is_active == 1 ? 'Active' : 'Lock';
            });

            const columns = [
                {
                    header: 'Tài khoản',
                    key: 'username',
                    width: 20
                },
                {
                    header: 'Tên hiển thị',
                    key: 'full_name',
                    width: 30
                },
                {
                    header: 'Email',
                    key: 'email',
                    width: 30
                },
                {
                    header: 'Trạng thái',
                    key: 'statusName',
                    width: 15
                }
            ];
            const sheetName = 'User';
            const fileName = 'nguoi dung.xlsx';

            this.exportExcelCommon(list, columns, sheetName, fileName);
        }
    }
};
</script>


<style scoped>
.avatar {
    width: 1rem;
    height: 1rem;
}
</style>
