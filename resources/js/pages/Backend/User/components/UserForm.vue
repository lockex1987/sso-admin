<template>
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form @submit.prevent="submitForm()"
                    novalidate>
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{id ? 'Cập nhật' : 'Thêm mới'}}
                            người dùng
                        </h4>

                        <button type="button"
                            class="close"
                            data-dismiss="modal">
                            &times;
                        </button>
                    </div>

                    <div class="modal-body text-body">
                        <div class="mb-3 text-center">
                            <label class="d-block mb-0 cursor-pointer">
                                <img class="rounded-circle avatar object-fit-cover"
                                    :src="oldAvatarUrl"
                                    title="Đổi ảnh đại diện"
                                    onerror="this.src = '/images/user-avatar.png'"
                                    ref="theImage" />

                                <input type="file"
                                    ref="avatarFile"
                                    @change="previewAvatar()"
                                    accept=".png,.jpg,.jpeg,.gif;capture=camera"
                                    class="d-none">
                            </label>

                            <div class="text-muted font-size-0.75 mt-3">
                                * Click vào ảnh đại diện để đổi ảnh
                            </div>
                        </div>

                        <div class="form-group validate-container">
                            <label class="required">
                                Tên đăng nhập
                            </label>
                            <input type="text"
                                v-model.trim="username"
                                class="form-control"
                                data-validation="required|minLength:4|maxLength:50"
                                data-validation-regex="^[a-zA-Z0-9_\.]+$"
                                data-validation-regex-message="Tên tài khoản là ký tự tiếng Việt không dấu, chữ số (a-z, A-Z, 0-9), không chứa dấu cách, có thể chứa ký tự đặc biệt là gạch dưới (_) hoặc dấu chấm (.)" />
                        </div>

                        <div class="form-group validate-container">
                            <label class="required">
                                Tên hiển thị
                            </label>
                            <input type="text"
                                v-model.trim="fullName"
                                class="form-control"
                                data-validation="required|maxLength:100" />
                        </div>

                        <div class="form-group validate-container">
                            <label class="required">
                                Email
                            </label>
                            <input type="text"
                                v-model.trim="email"
                                class="form-control"
                                data-validation="required|email|maxLength:200" />
                        </div>

                        <div class="form-group validate-container">
                            <label :class="{ 'required': id == null }">
                                Mật khẩu
                            </label>

                            <div class="input-group">
                                <input v-model.trim="password"
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    class="form-control"
                                    placeholder="Mật khẩu"
                                    data-validation="required|password|passwordStrong|maxLength:50"
                                    autocomplete="new-password"
                                    :data-ignore-validate="id != null">

                                <div class="input-group-append">
                                    <span class="input-group-text cursor-pointer"
                                        @click="togglePassword()">
                                        <i class="la"
                                            :class="[showPassword ? 'la-eye-slash' : 'la-eye']"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group validate-container">
                            <label class="required">
                                Tổ chức
                            </label>

                            <!-- TODO: Xóa orgComp -->
                            <tree-chooser ref="orgComp"
                                v-model="organization"
                                :options="orgList"
                                @change="selectOrganization()" />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit"
                            class="btn btn-primary">
                            {{id ? 'Cập nhật' : 'Thêm mới'}}
                            <span class="spinner-border spinner-border-sm"
                                v-show="isSaving"></span>
                        </button>

                        <button type="button"
                            class="btn btn-outline-secondary"
                            data-dismiss="modal">
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
            // Các thông tin của form
            username: '',
            fullName: '',
            email: '',
            password: '',
            organization: {},
            oldAvatarUrl: '',
            avatar: null,

            // ID của bản ghi đang sửa
            // Nếu khác null thì là sửa, nếu là null thì là thêm mới
            id: null,

            // Đang lưu thông tin
            isSaving: false,

            // Có hiển thị password hay không
            showPassword: false,

            // Danh sách tổ chức (dùng để chọn tổ chức)
            orgList: []
        };
    },

    mounted() {
        $(this.$el).on('hidden.bs.modal', () => {
            // Xóa các thông báo lỗi cũ nếu có
            CV.clearErrorMessages(this.$el);

            this.resetInfo();
        });

        this.getOrgList();
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
            this.username = '';
            this.fullName = '';
            this.email = '';
            this.password = '';
            this.organization = {};
            this.oldAvatarUrl = '';

            this.avatar = null;
            this.$refs.avatarFile.value = '';

            this.id = null;
        },

        /**
         * Bật form thêm mới.
         */
        openCreateForm() {
            this.openModal();
        },

        /**
         * Mở form cập nhật.
         */
        openUpdateForm(user) {
            this.username = user.username;
            this.fullName = user.full_name;
            this.email = user.email;
            // Không hiển password cũ
            if (user.organization) {
                this.organization = user.organization;
            } else {
                this.organization = {};
            }
            this.oldAvatarUrl = user.avatar;

            this.id = user.id;

            this.openModal();
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
            let formValid = true;
            if (CV.invalidForm(this.$el)) {
                formValid = false;
            }
            /*
            if (this.organization.id === null || this.organization.id === undefined) {
                if (formValid) {
                    this.$refs.orgComp.$el.focus();
                }
                formValid = false;
                // CV.showError(this.$refs.orgComp.$el, 'Vui lòng nhập trường này');
            }
            */
            if (!formValid) {
                return;
            }

            // Đánh dấu đang xử lý
            this.isSaving = true;

            // Gọi lên server
            const params = new FormData();
            params.append('username', this.username);
            params.append('fullName', this.fullName);
            params.append('email', this.email);
            params.append('organizationId', this.organization.id);
            if (this.avatar) {
                params.append('avatar', this.avatar);
            }
            if (this.id) {
                params.append('id', this.id);
            }
            if (this.password) {
                params.append('password', this.password);
            }
            const { data } = await axios.post('/user/store', params);

            // Đánh dấu đã xử lý xong
            this.isSaving = false;

            if (data.code == 0) {
                // Thông báo thành công
                noti.success(this.id ? 'Cập nhật thành công' : 'Thêm mới thành công');

                // Đóng modal
                this.closeModal();

                // Tìm kiếm lại
                this.$emit('search-again');
            }
        },

        /**
         * Ẩn/hiện password.
         */
        togglePassword() {
            this.showPassword = !this.showPassword;
        },

        /**
         * Người dùng chọn tổ chức rồi thì ẩn thông báo lỗi đi.
         */
        selectOrganization() {
            // CV.clearSingleErrorMessage(this.$refs.orgComp.$el);
        },

        /**
         * Lấy tất cả dữ liệu tổ chức.
         */
        async getOrgList() {
            const { data } = await axios.get('/organization/all');

            // Chuẩn hóa cấu trúc dữ liệu của cây
            this.orgList = data.map(e => {
                return {
                    id: e.id,
                    name: e.name,
                    parentId: e.parent_id,
                    path: e.path
                };
            });
        },

        /**
         * Xem trước ảnh avatar khi chọn file ảnh.
         */
        previewAvatar() {
            this.avatar = this.$refs.avatarFile.files[0];
            this.$refs.theImage.src = URL.createObjectURL(this.avatar);
        }
    }
};
</script>


<style scoped>
.avatar {
    width: 100px;
    height: 100px;
}
</style>
