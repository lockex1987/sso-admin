<template>
    <div>
        <top-header :paths="['File log']" />

        <div v-if="logList">
            <div class="text-danger"
                v-if="logList.length == 0">
                Không có file nào
            </div>

            <div v-else>
                <div v-for="log in logList"
                    :key="log"
                    class="mb-3">
                    <a href="#"
                        @click.prevent="download(log)">
                        {{log}}
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    data() {
        return {
            // Danh sách file log
            logList: null
        };
    },

    mounted() {
        this.getLogList();
    },

    methods: {
        /**
         * Lấy danh sách file.
         */
        async getLogList() {
            const { data } = await axios.get('/log-file/list');
            this.logList = data;
        },

        /**
         * Download file.
         * Phải gọi AJAX thì mới truyền được token, qua bước kiểm tra đăng nhập.
         * TODO: Xóa nội dung, xóa file
         */
        async download(fileName) {
            const params = {
                fileName: fileName
            };
            const { data } = await axios.get('/log-file/download', {
                params,
                responseType: 'blob'
            });
            const blob = new Blob([data]);
            CommonUtils.downloadBlob(blob, fileName);
        }
    }
};
</script>
