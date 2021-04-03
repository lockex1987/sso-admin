<template>
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form @submit.prevent="submitForm()" novalidate>
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{id ? 'Cập nhật' : 'Thêm mới'}} huyện / quận
                        </h4>
                        <button type="button" class="close" data-dismiss="modal">
                            &times;
                        </button>
                    </div>

                    <div class="modal-body text-body">
                        <div class="form-group validate-container">
                            <label class="required">
                                Mã
                            </label>

                            <input type="text"
                                    v-model.trim="code"
                                    class="form-control"
                                    data-validation="required|maxLength:100"/>
                        </div>

                        <div class="form-group validate-container">
                            <label class="required">
                                Tên
                            </label>

                            <input type="text"
                                    v-model.trim="name"
                                    class="form-control"
                                    data-validation="required|maxLength:100"/>
                        </div>

                        <div class="form-group validate-container">
                            <label class="required">
                                Loại
                            </label>

                            <single-select
                                    :options="typeList"
                                    v-model="type"
                                    :show-clear="false"/>
                        </div>

                        <div class="form-group validate-container">
                            <label class="required">
                                Tỉnh / thành phố
                            </label>

                            <single-select
                                    :options="provinceList"
                                    v-model="province"
                                    :show-clear="false"
                                    :has-search="true"/>
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
    props: {
        provinceList: Array,
        typeList: Array
    },

    data() {
        return {
            // Các thông tin của form
            code: '',
            name: '',
            province: {},
            type: {},

            // ID của bản ghi đang sửa
            // Nếu khác null thì là sửa, nếu là null thì là thêm mới
            id: null,

            // Đang lưu thông tin
            isSaving: false
        };
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
            this.code = '';
            this.name = '';
            this.type = {};
            this.province = {};

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
        openUpdateForm(district) {
            this.code = district.code;
            this.name = district.name;
            this.type = {
                id: district.type
            };
            this.province = {
                id: district.province_id
            };

            this.id = district.id;

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
            if (CV.invalidForm(this.$el)) {
                return;
            }

            // Đánh dấu đang xử lý
            this.isSaving = true;

            // Gọi lên server
            const params = {
                code: this.code,
                name: this.name,
                type: this.type.id,
                provinceId: this.province.id
            };
            if (this.id) {
                params.id = this.id;
            }

            const { data } = await axios.post('/district/store', params);

            // Đánh dấu đã xử lý xong
            this.isSaving = false;

            if (data.code == 0) {
                // Thông báo thành công
                noti.success(this.id ? 'Cập nhật thành công' : 'Thêm mới thành công');

                // Đóng modal
                this.closeModal();

                // Tìm kiếm lại
                this.$emit('stored');
            }
        }
    }
};
</script>
