<template>
    <div class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form @submit.prevent="submitForm()" novalidate>
                    <div class="modal-header">
                        <h4 class="modal-title">
                            Vai trò của người dùng {{user.username}}
                        </h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body text-body">
                        <div class="form-inline justify-content-end">
                            <!-- Không submit khi nhấn Enter ở ô lọc -->
                            <input type="text"
                                    class="form-control mb-2"
                                    v-model.trim="searchText"
                                    placeholder="Từ khóa"
                                    @keydown.enter.prevent="">
                        </div>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">Mã vai trò</th>
                                    <th class="text-center">Tên vai trò</th>
                                    <th class="text-center">Gán vai trò</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="r in filteredRoleList" :key="r.id">
                                    <td>
                                        {{r.code}}
                                    </td>
                                    <td>
                                        {{r.name}}
                                    </td>
                                    <td class="text-center">
                                        <label class="custom-control custom-checkbox custom-control-lg custom-control-animated custom-control-highlighted custom-control-outlined custom-control-inline mb-0 mr-0">
                                            <input type="checkbox"
                                                    class="custom-control-input"
                                                    v-model='r.isGranted'>
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
            // Người dùng hiện tại
            user: {},

            // Danh sách vai trò
            roleList: [],

            // Đang xử lý
            isSaving: false,

            // Tìm kiếm vai trò
            searchText: ''
        };
    },

    computed: {
        filteredRoleList() {
            const text = this.searchText.toLowerCase();
            return this.roleList.filter(r => {
                return r.code.toLowerCase().includes(text) ||
                    r.name.toLowerCase().includes(text);
            });
        }
    },

    mounted() {
        $(this.$el).on('hidden.bs.modal', () => {
            // Xóa các thông báo lỗi cũ nếu có
            CV.clearErrorMessages(this.$el);

            this.resetInfo();
        });

        this.getRoleList();
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
            this.user = {};
            this.searchText = '';
        },

        /**
         * Mở form cập nhật.
         */
        openUpdateForm(user) {
            this.user = user;

            this.openModal();

            this.getRolesOfUser();
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
                id: this.user.id,
                roles: this.roleList.filter(r => r.isGranted).map(r => r.id)
            };

            const { data } = await axios.post('/user/roles', params);

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
         * Lấy toàn bộ danh sách quyền.
         */
        async getRoleList() {
            const { data } = await axios.get('/role/all');
            data.forEach(e => {
                e.isGranted = false;
            });
            this.roleList = data;
        },

        /**
         * Lấy danh sách quyền của người dùng.
         */
        async getRolesOfUser() {
            const params = {
                id: this.user.id
            };
            const { data } = await axios.get('/user/roles', { params });
            this.roleList.forEach(r => {
                const isGranted = data.find(x => x.id == r.id);
                r.isGranted = !!isGranted;
            });
        }
    }
};
</script>
