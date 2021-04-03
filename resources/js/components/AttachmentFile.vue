<template>
    <div>
        <div class="mb-2 d-flex"
                v-for="(file, idx) in files"
                :key="file.id">
            <a :href="'/storage/' + file.path.replace('public/', '')"
                    class="text-decoration-none"
                    target="_blank">
                <i class="la la-file"></i>
                {{file.file_name}}
            </a>

            <span class="cursor-pointer text-danger font-weight-bold ml-auto"
                    title="Xóa"
                    @click="removeFile(idx, file)">
                <i class="la la-times"></i>
            </span>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        // Danh sách các file cũ
        files: Array,

        // Danh sách các file xóa đi
        deletedFiles: Array
    },

    methods: {
        removeFile(idx, file) {
            this.files.splice(idx, 1);
            this.deletedFiles.push(file);
        }
    }
};
</script>
