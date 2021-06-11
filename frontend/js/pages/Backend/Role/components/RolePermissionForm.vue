<template>
    <div class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form @submit.prevent="submitForm()" novalidate>
                    <div class="modal-header">
                        <h4 class="modal-title">
                            Quyền của vai trò {{role.code}}
                        </h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body text-body">
                        <div class="form-inline justify-content-end">
                            <input type="text"
                                    class="form-control mb-2"
                                    v-model.trim="searchText"
                                    placeholder="Từ khóa"
                                    @keyup.enter.stop="stopSubmit($event)">
                        </div>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">Mã quyền</th>
                                    <th class="text-center">Tên quyền</th>
                                    <th class="text-center">Gán quyền</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="p in filteredPermissionList" :key="p.id">
                                    <td>
                                        {{p.code}}
                                    </td>
                                    <td>
                                        {{p.name}}
                                    </td>
                                    <td class="text-center">
                                        <label class="custom-control custom-checkbox custom-control-lg custom-control-animated custom-control-highlighted custom-control-outlined custom-control-inline mb-0 mr-0">
                                            <input type="checkbox"
                                                    class="custom-control-input"
                                                    v-model='p.isGranted'>
                                            <span class="custom-control-label" style="w-0">
                                                &nbsp;
                                            </span>
                                        </label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            Lưu lại
                            <span class="spinner-border spinner-border-sm" v-show="isSaving"></span>
                        </button>

                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                            Đóng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    data() {
        return {
            // Vai trò hiện tại
            role: {},

            // Danh sách quyền
            permissionList: [],

            // Đang xử lý
            isSaving: false,

            // Tìm kiếm quyền
            searchText: ''
        };
    },

    computed: {
        filteredPermissionList() {
            const text = this.searchText.toLowerCase();
            return this.permissionList.filter(p => {
                return p.code.toLowerCase().includes(text)
                    || p.name.toLowerCase().includes(text);
            });
        }
    },

    mounted() {
        $(this.$el).on('hidden.bs.modal', () => {
            // Xóa các thông báo lỗi cũ nếu có
            CV.clearErrorMessages(this.$el);

            this.resetInfo();
        });

        this.getPermissionList();
    },

    methods: {
        /**
         * Hiển thị modal.
         */
        openModal() {
            $(this.$el).modal('show');
        },

        /**
         * Đóng modal.
         */
        closeModal() {
            $(this.$el).modal('hide');
        },

        /**
         * Reset các thông tin.
         */
        resetInfo() {
            this.role = {};
            this.searchText = '';
        },

        /**
         * Mở form cập nhật.
         */
        openUpdateForm(role) {
            this.role = role;

            this.openModal();

            this.getPermissionsOfRole();
        },

        /**
         * Lấy toàn bộ danh sách quyền.
         */
        async getPermissionList() {
            const { data } = await axios.get('/permission/all');
            data.forEach(e => {
                e.isGranted = false;
            });
            this.permissionList = data;
        },

        /**
         * Lấy danh sách quyền của người dùng.
         */
        async getPermissionsOfRole() {
            const params = {
                id: this.role.id
            };
            const { data } = await axios.get('/role/permissions', { params });
            this.permissionList.forEach(e => {
                const isGranted = data.find(x => x.id == e.id);
                e.isGranted = !!isGranted;
            });
        },

        /**
         * Lưu thông tin.
         */
        async submitForm() {
            // Nếu đang xử lý rồi thì dừng lại
            if (this.isSaving) {
                return;
            }

            // Validate form dữ liệu
            if (CV.invalidForm(this.$el)) {
                return;
            }

            // Đánh dấu đang xử lý
            this.isSaving = true;

            // Gọi lên server
            const params = {
                id: this.role.id,
                permissions: this.permissionList.filter(p => p.isGranted).map(p => p.id)
            };

            const { data } = await axios.post('/role/permissions', params);

            // Đánh dấu đã xử lý xong
            this.isSaving = false;

            if (data.code == 0) {
                // Thông báo thành công
                noti.success('Cập nhật thành công');

                // Đóng modal
                this.closeModal();
            } else {
                noti.error(data.message);
            }
        },

        /**
         * TODO: Không submit khi nhấn Enter ở ô lọc.
         */
        stopSubmit(evt) {
            evt.stopPropagation();
            evt.preventDefault();
            console.log(evt);
            return false;
        }
    }
};
</script>
