<template>
    <textarea></textarea>
</template>


<script>
export default {
    props: {
        value: {
            type: String,
            default: ''
        },

        oldValue: {
            type: String,
            default: ''
        }
    },

    watch: {
        oldValue() {
            $(this.$el).summernote('code', this.oldValue ?? '');
        }
    },

    mounted() {
        this.initEditor();
    },

    methods: {
        /**
         * Khởi tạo HTML editor.
         */
        initEditor() {
            const vm = this;

            $(this.$el).summernote({
                minHeight: 300,
                maxHeight: null, // tự động điều chỉnh độ dài theo nội dung
                placeholder: 'Bắt đầu viết hoặc nhập...',
                lang: 'vi-VN', // tiếng Việt

                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview']]
                ],

                callbacks: {
                    onImageUpload_x(files) {
                        // Validate
                        let hasError = false;
                        const maxFileSize = 100;
                        for (let i = 0; i < files.length; i++) {
                            const file = files[i];
                            let filesize = file.size;
                            filesize = parseFloat(filesize / 1024 / 1024).toFixed(2);
                            if (filesize > maxFileSize) {
                                noti.error(`File <b>${file.name}</b> có dung lượng file vượt quá ${maxFileSize} MB (${filesize} MB)`);
                                hasError = true;
                            }
                        }

                        if (hasError) {
                            return;
                        }

                        // console.log('Vao 1');

                        // Upload ảnh lên server
                        for (let i = 0; i < files.length; i++) {
                            const file = files[i];
                            const params = new FormData();
                            params.append('image', file);

                            axios.post('/admin/content/upload-image', params)
                                .then(resp => {
                                    const data = resp.data;
                                    console.log(data);

                                    if (data.code != 0) {
                                        noti.error('Có lỗi xảy ra. Vui lòng chọn lại sau!');
                                    } else {
                                        // Tạo thẻ img và thêm vào editor
                                        const url = data.data.file_path;
                                        const image = document.createElement('img');
                                        image.src = url;
                                        image.style.width = '50%';
                                        $(vm.$refs.contentHtml).summernote('insertNode', image);
                                    }
                                });
                        }
                    },

                    onChange(contents, $editable) {
                        // console.log(contents);
                        vm.$emit('input', contents);

                        // const contentTag = $('<div>' + contents + '</div>')[0];
                        // const contentText = contentTag.textContent.trim();

                        // CV.clearSingleErrorMessage(this.$el);

                        /*
                        if (vm.isEmpty(contentTag)) {
                            showError(vm.$refs.contentHtml, 'Vui lòng nhập nội dung bài viết');
                        }
                        */

                        /*
                        if (contentText.length > 16348) {
                            // CV.showError(this.$el, 'Nội dung bài viết quá lớn');
                        }
                        */
                    }
                }
            });
        }
    }
};
</script>
