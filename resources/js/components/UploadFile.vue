<template>
    <div>
        <div class="d-flex justify-content-between mb-2">
            <span>
                {{title}}
            </span>

            <div>
                <input ref="fileInput"
                        type="file"
                        class="d-none"
                        :accept="isImage ? 'image/*' : 'image/*,.png,.jpeg,.jpg,.gif,.zip,.xls,.xlsx,.doc,.docx,.pdf;capture=camera'"/>

                <span class="cursor-pointer text-success"
                        @click="openChooseFileDialog()"
                        v-show="!files || (files && files.length < maxFile)">
                    <i class="la la-plus"></i>
                    Thêm
                </span>
            </div>
        </div>

        <div v-for="(f, idx) in files"
                class="d-flex justify-content-between mb-2">
            <span>
                <i class="la la-file" v-if="!isImage"></i>
                <img class="preview-image object-fit-cover mr-2 rounded"
                        :src="getImageSource(f)"
                        v-else/>

                {{f.name}}
            </span>

            <span class="cursor-pointer text-danger font-weight-bold"
                    title="Xóa"
                    @click="removeFile(idx)">
                <i class="la la-times"></i>
            </span>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        // Mảng các file
        files: Array,
        maxFile: {
            type: Number,
            default: 10
        },
        title: {
            type: String,
            default: 'Đính kèm file'
        },
        isImage: {
            type: Boolean,
            default: false
        }
    },

    mounted() {
        this.addEvent();
    },

    methods: {
        addEvent() {
            this.$refs.fileInput.addEventListener('change', (evt) => {
                const arr = evt.target.files;
                for (let i = 0; i < arr.length; i++) {
                    const f = arr[i];
                    this.files.push(f);
                }
                this.$refs.fileInput.value = '';
            });
        },

        openChooseFileDialog() {
            this.$refs.fileInput.click();
        },

        removeFile(idx) {
            this.files.splice(idx, 1);
        },

        getImageSource(file) {
            return URL.createObjectURL(file);
        }
    }
};
</script>


<style scoped lang="scss">
.preview-image {
    width: 2rem;
    height: 2rem;
}
</style>