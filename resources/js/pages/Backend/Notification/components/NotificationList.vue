<template>
    <div>
        <div class="form-inline mt-5">
            <input type="text"
                    class="form-control mb-2 mr-sm-2"
                    v-model.trim="searchText"
                    placeholder="Từ khóa"
                    @input="debouncedSearch()">

            <!-- TODO: Lọc theo trạng thái -->
        </div>

        <div class="mb-3">
            <button class="btn btn-primary" @click="publish()">
                Test publish
            </button>
        </div>

        <div class="datatable-wrapper">
            <table class="table table-bordered"
                    ref="searchResult"
                    v-show="notificationList.length > 0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px">
                            #
                        </th>
                        <th class="text-center" style="width: 215px;">
                            Thời điểm tạo
                        </th>
                        <th class="text-center" style="width: 215px;">
                            Đánh dấu đã đọc
                        </th>
                        <th class="text-center">
                            Thông báo
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="noti in notificationList"
                            :key="noti.id"
                            :class="{
                                'bg-light': noti.read_at == null
                            }">
                        <td class="text-center">
                            {{noti.stt}}
                        </td>
                        <td class="text-center">
                            {{formatDateTime(noti.created_at)}}
                            <!-- TODO: Mấy phút trước -->
                        </td>
                        <td class="text-center">
                            <!-- TODO: Hover thì hiển thị -->
                            <i class="cursor-pointer la la-lg la-bookmark text-primary"
                                    title="Đánh dấu đã đọc"
                                    @click="markRead(noti)"></i>
                        </td>
                        <td>
                            <div v-html="noti.message"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
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

            // Đối tượng datatable
            datatable: null
        };
    },

    mounted() {
        this.initDatatable();
    },

    methods: {
        /**
         * Khởi tạo đối tượng datatable.
         */
        initDatatable() {
            this.datatable = new Datatable({
                table: this.$refs.searchResult,
                ajax: (page, pageSize, sortColumn, sortDirection) => {
                    const params = {
                        search: this.searchText,
                        page: page - 1,
                        size: pageSize
                    };
                    return axios.get('/notification/search', { params });
                },
                bindItemsCallback: (items) => {
                    this.notificationList = items;
                },
                getTotalAndData: ({ data }) => {
                    return {
                        total: data.total,
                        data: data.data
                    };
                },
                showLoading: true
            });
        },

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
        search() {
            this.datatable.reload();
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

        async publish() {
            await axios.post('/notification/publish');
        }
    }
};
</script>
