<template>
    <label class="upload-image position-relative mx-auto cursor-pointer d-block mb-0">
        <img class="object-fit-cover w-100 h-100 rounded"
                :src="value ? getImageSource(value) : (imageUrl || '/images/placeholder-image.png')"
                onerror="this.src = '/images/placeholder-image.png'"
                title="Click để thay ảnh">

        <input ref="fileInput"
                type="file"
                class="d-none"
                accept="image/*"/>
    </label>
</template>


<script>
export default {
    props: {
        // Đối tượng file
        value: Object,
        // Đường dẫn URL cũ
        imageUrl: String
    },

    mounted() {
        this.addEvent();
    },

    methods: {
        addEvent() {
            this.$refs.fileInput.addEventListener('change', (evt) => {
                const arr = evt.target.files;
                this.$emit('input', arr[0]);
                this.$refs.fileInput.value = '';
            });
        },

        getImageSource(file) {
            return URL.createObjectURL(file);
        }
    }
};
</script>


<style scoped lang="scss">
.upload-image {
    width: 8rem;
    height: 8rem;
}
</style>