<template>
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form @submit.prevent="submitForm()" novalidate>
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{id ? 'Cập nhật' : 'Thêm mới'}} xã / phường
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
                                Huyện / quận
                            </label>

                            <div class="border rounded d-flex cascade-select">
                                <single-select
                                        placeholder="Tỉnh / thành phố"
                                        :options="provinceList"
                                        v-model="province"
                                        :show-clear="true"
                                        :has-search="true"
                                        @change="updateDistrictSelect()"/>

                                <single-select
                                        class="ml-2"
                                        placeholder="Huyện / quận"
                                        :options="districtList"
                                        v-model="district"
                                        :show-clear="false"
                                        :has-search="true"
                                        v-show="districtList.length > 0"/>
                            </div>
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
        // Danh sách tỉnh thành phố
        provinceList: Array,
        typeList: Array
    },

    data() {
        return {
            // Các thông tin của form
            code: '',
            name: '',
            type: {},

            // ID của bản ghi đang sửa
            // Nếu khác null thì là sửa, nếu là null thì là thêm mới
            id: null,

            // Đang lưu thông tin
            isSaving: false,

            province: {},
            district: {},
            districtList: []
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
        updateDistrictSelect() {
            this.district = {};

            if (this.province.id) {
                this.getDistrictList();
            } else {
                this.districtList = [];
            }
        },

        async getDistrictList() {
            const params = {
                provinceId: this.province.id
            };
            const { data } = await axios.get('/district/search', { params });
            this.districtList = data;
        },

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
            this.district = {};
            this.districtList = [];

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
        openUpdateForm(commune) {
            this.code = commune.code;
            this.name = commune.name;
            this.type = {
                id: commune.type
            };
            this.province = {
                id: commune.province_id
            };
            this.district = {
                id: commune.district_id
            };
            this.getDistrictList();

            this.id = commune.id;

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
                provinceId: this.province.id,
                districtId: this.district.id
            };
            if (this.id) {
                params.id = this.id;
            }

            const { data } = await axios.post('/commune/store', params);

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
