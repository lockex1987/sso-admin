<template>
    <form @submit.prevent="submitForm()" novalidate>
        <the-breadcrumb :paths="['Nội dung', (id ? 'Cập nhật' : 'Thêm mới') + ' nội dung']"/>

        <div class="row">
            <div class="col-lg-6">
                <div class="form-group validate-container">
                    <label class="required">
                        Tiêu đề
                    </label>

                    <input type="text"
                            v-model.trim="title"
                            class="form-control"
                            data-validation="required|maxLength:500"/>
                </div>

                <div class="form-group validate-container">
                    <label class="required">
                        Mô tả
                    </label>

                    <textarea type="text"
                            v-model.trim="description"
                            class="form-control"
                            data-validation="required|maxLength:1000"></textarea>
                </div>

                <div class="form-group validate-container">
                    <label>
                        Trạng thái
                    </label>

                    <single-select
                            placeholder=""
                            :options="statusList"
                            v-model="status"
                            :show-clear="false"/>
                </div>

                <div class="form-group validate-container">
                    <label>
                        Loại
                    </label>

                    <single-select
                            placeholder=""
                            :options="typeList"
                            v-model="type"
                            :show-clear="false"/>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group validate-container">
                    <label>
                        Thumbnail
                    </label>

                    <upload-image
                            v-model="thumbnailFile"
                            :image-url="thumbnailUrl"/>
                </div>

                <div class="mb-3">
                    <upload-file
                            :files="attachments"
                            :max-file="5"/>

                    <attachment-file
                            :files="oldAttachments"
                            :deleted-files="deletedAttachments"/>
                </div>
            </div>
        </div>

        <div class="form-group validate-container">
            <label class="required">
                Nội dung
            </label>

            <editor
                    v-model="content"
                    :old-value="oldContent"/>
        </div>

        <div v-if="images.length > 0">
            <div class="mb-3">
                <label>
                    Danh sách ảnh trong nội dung
                </label>

                <div class="d-flex images">
                    <div v-for="(img, idx) in images"
                            :key="img"
                            class="position-relative pr-3"
                            :class="{
                                'ml-4': idx > 0
                            }">
                        <img :src="'/storage/' + img"
                                class="rounded object-fit-cover"/>

                        <i class="la la-times text-danger cursor-pointer position-absolute bottom-right"
                                style="bottom: 0; right: 0;"
                                @click="deleteContentImage(img, idx)"
                                title="Xóa"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-right">
            <button type="submit"
                    class="btn btn-primary">
                {{id ? 'Cập nhật' : 'Thêm mới'}}
                <span class="spinner-border spinner-border-sm" v-show="isSaving"></span>
            </button>

            <button type="button"
                    class="btn btn-outline-secondary"
                    @click="closeForm()">
                Đóng
            </button>
        </div>
    </form>
</template>


<script>
export default {
    props: {
        statusList: Array,
        typeList: Array
    },

    data() {
        return {
            // Các thông tin của form
            title: '',
            description: '',
            oldContent: '',
            content: '',
            status: {
                id: 1
            },
            type: {
                id: 1
            },
            attachments: [],
            oldAttachments: [],
            deletedAttachments: [],
            thumbnailFile: null,
            thumbnailUrl: '',
            images: [],

            // ID của bản ghi đang sửa
            // Nếu khác null thì là sửa, nếu là null thì là thêm mới
            id: null,

            // Đang lưu thông tin
            isSaving: false
        };
    },

    methods: {
        /**
         * Đóng modal.
         * Không bật popup
         * Nếu bật popup, sẽ bị xung đột với summernote, khi bật và tắt dialog của summernote (sự kiện hidden.bs.modal)
         */
        closeForm() {
            // Xóa các thông báo lỗi cũ nếu có
            CV.clearErrorMessages(this.$el);

            this.resetInfo();

            this.$emit('close');
        },

        /**
         * Reset các thông tin.
         */
        resetInfo() {
            this.title = '';
            this.description = '';
            this.oldContent = '';
            this.content = '';
            this.status = {
                id: 1
            };
            this.type = {
                id: 1
            };
            this.attachments = [];
            this.oldAttachments = [];
            this.deletedAttachments = [];
            this.thumbnailFile = null;
            this.thumbnailUrl = '';
            this.images = [];

            this.id = null;
        },

        /**
         * Mở form cập nhật.
         */
        openUpdateForm(content) {
            this.title = content.title;
            this.description = content.description;
            this.oldContent = content.content;
            this.content = content.content;
            this.status = {
                id: content.status
            };
            this.type = {
                id: content.type
            };
            this.attachments = [];
            this.oldAttachments = content.attachments;
            this.deletedAttachments = [];
            this.thumbnailFile = null;
            this.thumbnailUrl = content.thumbnail ? content.thumbnail : '';
            this.images = content.images ?? [];

            this.id = content.id;
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
                title: this.title,
                description: this.description,
                content: this.content,
                status: this.status.id,
                type: this.type.id
            };
            if (this.id) {
                params.id = this.id;
            }

            const formData = new FormData();
            for (const [key, value] of Object.entries(params)) {
                formData.append(key, value);
            }
            this.attachments.forEach(file => {
                formData.append('attachments[]', file);
            });
            this.deletedAttachments.forEach(file => {
                formData.append('deletedAttachments[]', file.id);
            });
            if (this.thumbnailFile) {
                formData.append('thumbnail', this.thumbnailFile);
            }
            const { data } = await axios.post('/content/store', formData);

            // Đánh dấu đã xử lý xong
            this.isSaving = false;

            if (data.code == 0) {
                // Thông báo thành công
                noti.success(this.id ? 'Cập nhật thành công' : 'Thêm mới thành công');

                // Đóng form
                this.closeForm();

                // Tìm kiếm lại
                this.$emit('stored');
            } else if (data.code == 1) {
                noti.error(data.message);
            }
        },

        /**
         * Xóa ảnh ở nội dung bài viết.
         */
        async deleteContentImage(img, idx) {
            const params = {
                contentId: this.id,
                image: img
            };
            const { data } = await axios.delete('/content/delete-image', { data: params });
            if (data.code == 0) {
                this.images.splice(idx, 1);
            }
        }
    }
};
</script>


<style scoped lang="scss">
.images {
    img {
        width: 4rem;
        height: 4rem;
    }
}
</style>
