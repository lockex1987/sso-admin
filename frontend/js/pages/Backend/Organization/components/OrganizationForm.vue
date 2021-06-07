<template>
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form @submit.prevent="submitForm()" novalidate>
                    <div class="modal-header bg-light">
                        <h4 class="modal-title">
                            {{id ? 'Cập nhật' : 'Thêm mới'}} tổ chức
                        </h4>
                        <button type="button" class="close" data-dismiss="modal">
                            &times;
                        </button>
                    </div>

                    <div class="modal-body text-body">
                        <div class="form-group validate-container">
                            <label class="required">
                                Tên tổ chức
                            </label>

                            <input type="text"
                                    v-model.trim="name"
                                    class="form-control"
                                    data-validation="required|maxLength:200"/>
                        </div>

                        <div class="form-group validate-container">
                            <label>
                                Tổ chức cấp trên
                            </label>

                            <tree-chooser
                                    ref="parentOrgComp"
                                    v-model="parentOrg"
                                    :ignored-id="id"
                                    :options="orgList"
                                    :show-search="false"
                                    :show-clear="true"
                                    placeholder=""/>
                        </div>

                        <div class="form-group validate-container">
                            <label>
                                Mô tả
                            </label>

                            <textarea v-model.trim="description"
                                    class="form-control"
                                    data-validation="maxLength:1000"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            {{id ? 'Cập nhật' : 'Thêm mới'}}
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
            // Các thông tin của form
            name: '',
            description: '',
            // Tổ chức cha
            parentOrg: {},

            // ID của bản ghi đang sửa
            // Nếu khác null thì là sửa, nếu là null thì là thêm mới
            id: null,

            // Đang lưu thông tin
            isSaving: false
        };
    },

    props: {
        // Danh sách tổ chức (dùng để chọn tổ chức cha)
        orgList: Array
    },

    mounted() {
        $(this.$el).on('hidden.bs.modal', () => {
            // Xóa các thông báo lỗi cũ nếu có
            CV.clearErrorMessages(this.$el);

            this.resetInfo();
        });
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
            this.name = '';
            this.description = '';
            this.parentOrg = {};

            this.id = null;

            this.$refs.parentOrgComp.closeDropdown();
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
        openUpdateForm(org) {
            this.name = org.name;
            this.description = org.description;
            if (org.parent) {
                this.parentOrg = {
                    id: org.parent.id,
                    name: org.parent.name
                };
            } else {
                this.parentOrg = {};
            }

            this.id = org.id;

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

            if (!formValid) {
                return;
            }

            // Đánh dấu đang xử lý
            this.isSaving = true;

            // Gọi lên server
            const params = {
                name: this.name,
                description: this.description,
                parentId: this.parentOrg.id
            };
            if (this.id) {
                params.id = this.id;
            }
            const { data } = await axios.post('/organization/store', params);

            // Đánh dấu đã xử lý xong
            this.isSaving = false;

            if (data.code == 0) {
                // Thông báo thành công
                noti.success(this.id ? 'Cập nhật thành công' : 'Thêm mới thành công');

                // Đóng modal
                this.closeModal();

                // Tìm kiếm lại
                this.$emit('stored');
            } else if (data.code == 1) {
                noti.error(data.message);
            }
        }
    }
};
</script>
