<template>
    <div>
        <the-breadcrumb :paths="['Log hệ thống']"/>

        <div class="d-flex mb-2">
            <date-range-picker-separate
                    :is-start-required="false"
                    :is-end-required="false"
                    :min-start-datex="minStartDate"
                    :max-start-datex="maxStartDate"
                    :min-end-datex="minEndDate"
                    :max-end-datex="maxEndDate"
                    :options="{ drops: 'down' }"
                    v-model="searchTime"
                    @change="search()"
                    style="width: 360px"/>

            <input type="text"
                    class="form-control ml-2"
                    style="width: 200px"
                    v-model.trim="ip"
                    placeholder="IP"
                    @input="debouncedSearch()">

            <single-select
                    class="ml-2"
                    style="width: 200px"
                    placeholder="Loại"
                    :options="typeList"
                    v-model="type"
                    :show-clear="true"
                    @change="search()"/>

            <input type="text"
                    class="form-control ml-2"
                    style="width: 200px"
                    v-model.trim="username"
                    placeholder="Tài khoản"
                    @input="debouncedSearch()">

            <button class="btn btn-danger ml-auto"
                    @click="deleteBatch()">
                Xóa
            </button>
        </div>

        <div class="datatable-wrapper">
            <table class="table table-bordered"
                    ref="searchResult"
                    v-show="logList.length > 0">
                <thead>
                    <tr>
                        <th class="text-center"
                                style="width: 50px">
                            #
                        </th>
                        <th class="text-center">
                            Thời gian
                        </th>
                        <th class="text-center">
                            IP
                        </th>
                        <th class="text-center">
                            Loại
                        </th>
                        <th class="text-center">
                            Tài khoản
                        </th>
                        <th class="text-center">
                            Mô tả
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="log in logList"
                            :key="log.id">
                        <td class="text-center">
                            {{log.stt}}
                        </td>
                        <td class="text-center">
                            {{log.created_at}}
                        </td>
                        <td>
                            {{log.ip}}
                        </td>
                        <td>
                            {{getTypeName(log.type)}}
                        </td>
                        <td>
                            {{log.user ? log.user.username : ''}}
                        </td>
                        <td>
                            {{log.description}}
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
            // Danh sách ứng dụng
            logList: [],

            // Đối tượng datatable
            datatable: null,

            // Tiêu chí tìm kiếm
            username: '',
            ip: '',
            type: {},
            typeList: [
                { id: 'login', name: 'Login' },
                { id: 'create_content', name: 'Thêm mới nội dung' },
                { id: 'update_content', name: 'Cập nhật nội dung' },
                { id: 'error_log', name: 'Log lỗi' }
            ],
            searchTime: {
                startDate: moment().subtract(7, 'days'),
                endDate: moment()
            },

            // Validate khoảng ngày của ngày bắt đầu
            minStartDate: moment().subtract(365, 'days'),
            maxStartDate: moment().add(365, 'days'),
            minEndDate: moment().subtract(365, 'days'),
            maxEndDate: moment().add(365, 'days')
        };
    },

    mounted() {
        this.initDatatable();
    },

    methods: {
        /**
         * Lấy các tiêu chí tìm kiếm.
         */
        getParams() {
            const params = {
                username: this.username,
                type: this.type.id,
                ip: this.ip
            };
            if (this.searchTime.startDate) {
                params.createdFrom = this.searchTime.startDate.format('YYYY-MM-DD') + ' 00:00:00';
            }
            if (this.searchTime.endDate) {
                params.createdTo = this.searchTime.endDate.format('YYYY-MM-DD') + ' 23:59:59';
            }
            return params;
        },

        /**
         * Khởi tạo đối tượng datatable.
         */
        initDatatable() {
            this.datatable = new Datatable({
                table: this.$refs.searchResult,
                ajax: (page, size, sortColumn, sortDirection) => {
                    const params = {
                        ...this.getParams(),
                        page: page,
                        size: size
                    };
                    return axios.get('/system-log/search', { params });
                },
                bindItemsCallback: (items) => {
                    this.logList = items;
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
         * Debounce hàm tìm kiếm sau nửa giây.
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
         * Xóa nhiều bản ghi.
         */
        deleteBatch() {
            noti.confirm('Bạn chắc chắn muốn xoá <b>TẤT CẢ</b> các bản ghi tìm kiếm được hay không?', async () => {
                const params = this.getParams();
                const { data } = await axios.delete('/system-log/destroy', { data: params });
                if (data.code == 0) {
                    noti.success('Xóa thành công');
                    this.search();
                }
            });
        },

        /**
         * Lấy tên của loại log.
         */
        getTypeName(type) {
            const obj = this.typeList.find(e => e.id == type);
            return obj?.name ?? type;
        }
    }
};
</script>
