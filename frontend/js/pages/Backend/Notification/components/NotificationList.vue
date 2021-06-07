<template>
    <div>
        <div class="form-inline">
            <input type="text"
                class="form-control mb-2 mr-sm-2"
                v-model.trim="searchText"
                placeholder="Từ khóa"
                @input="debouncedSearch()">

            <!-- TODO: Lọc theo trạng thái -->
        </div>

        <div class="mb-3">
            <button class="btn btn-primary"
                @click="publish()">
                Test publish
            </button>
        </div>

        <table class="table table-bordered"
            v-show="notificationList.length > 0">
            <thead>
                <tr>
                    <th class="text-center"
                        style="width: 50px">
                        #
                    </th>
                    <th class="text-center"
                        style="width: 215px;">
                        Thời điểm tạo
                    </th>
                    <th class="text-center"
                        style="width: 215px;">
                        Đánh dấu đã đọc
                    </th>
                    <th class="text-center">
                        Thông báo
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(noti, i) in notificationList"
                    :key="noti.id"
                    :class="{
                            'bg-light': !noti.read_at
                        }">
                    <td class="text-center">
                        {{pagi.from + i}}
                    </td>
                    <td class="text-center">
                        {{formatDateTime(noti.created_at)}}
                        <!-- TODO: Mấy phút trước -->
                    </td>
                    <td class="text-center text-primary">
                        <i class="cursor-pointer la la-lg la-bookmark"
                            title="Đánh dấu đã đọc"
                            @click="markRead(noti)"
                            v-if="!noti.read_at"></i>

                        <i class="fa fa-bookmark"
                            title="Đã đọc"
                            v-else></i>
                    </td>
                    <td>
                        <div v-html="noti.message"></div>
                    </td>
                </tr>
            </tbody>
        </table>

        <pagi @change="search"
            v-model="pagi"></pagi>
    </div>
</template>


<script>
export default {
    data() {
        return {
            // Danh sách kết quả tìm kiếm
            notificationList: [],

            // Text tìm kiếm
            searchText: '',

            // Đối tượng Pagi
            pagi: {}
        };
    },

    mounted() {
        this.search();
    },

    methods: {
        /**
         * Lọc theo từ khóa.
         */
        debouncedSearch: CommonUtils.debounce(
            function () {
                this.search();
            },
            500
        ),

        /**
         * Tìm kiếm.
         */
        async search(page = 1) {
            const params = {
                search: this.searchText,
                page: page,
                size: 10
            };
            const { data } = await axios.get('/notification/search', { params });
            this.pagi = data;
            this.notificationList = data.data;
        },

        /**
         * Đánh dấu đã đọc.
         */
        async markRead(noti) {
            const params = {
                id: noti.id
            };
            const { data } = await axios.post('/notification/mark-read', params);
            if (data.code == 0) {
                noti.read_at = true;
                // this.search();
            }
        },

        /**
         * Thử publish một thông báo.
         */
        async publish() {
            await axios.post('/notification/publish');
        }
    }
};
</script>
