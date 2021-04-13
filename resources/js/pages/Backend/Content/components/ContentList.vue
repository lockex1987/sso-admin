<template>
    <div>
        <div v-show="screen == 'list'">
            <the-breadcrumb :paths="['Nội dung']" />

            <div class="d-flex flex-wrap">
                <!-- Form tìm kiếm -->
                <input type="text"
                    class="form-control mb-2"
                    v-model.trim="searchText"
                    placeholder="Tiêu đề, mô tả"
                    @input="debouncedSearch()"
                    style="width: 200px;">

                <single-select class="ml-2 mb-2"
                    placeholder="Trạng thái"
                    :options="statusList"
                    v-model="status"
                    :show-clear="true"
                    @change="search()"
                    style="width: 200px;" />

                <single-select class="ml-2 mb-2"
                    placeholder="Loại"
                    :options="typeList"
                    v-model="type"
                    :show-clear="true"
                    @change="search()"
                    style="width: 200px;" />

                <button class="btn btn-primary btn-ripple ml-auto mb-2"
                    type="button"
                    @click="openCreateForm()">
                    Thêm mới
                </button>
            </div>

            <!-- Kết quả tìm kiếm -->
            <div class="datatable-wrapper">
                <table class="table table-bordered"
                    ref="searchResult"
                    v-show="contentList.length > 0">
                    <thead>
                        <tr>
                            <th class="text-center"
                                style="width: 50px">
                                STT
                            </th>
                            <th class="text-center">
                                Tiêu đề
                            </th>
                            <th class="text-center">
                                Mô tả
                            </th>
                            <th class="text-center"
                                style="width: 90px;">
                                Loại
                            </th>
                            <th class="text-center"
                                style="width: 120px;">
                                Trạng thái
                            </th>
                            <th class="text-center"
                                style="width: 145px;">
                                Ngày xuất bản
                            </th>
                            <th class="text-center"
                                style="width: 120px;">
                                Thao tác
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="content in contentList"
                            :key="content.id">
                            <td class="text-center">
                                {{content.stt}}
                            </td>
                            <td>
                                {{content.title}}
                            </td>
                            <td>
                                {{content.description}}
                            </td>
                            <td>
                                {{getTypeName(content.type)}}
                            </td>
                            <td :class="[content.status == 1 ? 'text-danger' : 'text-success']">
                                {{getStatusName(content.status)}}
                            </td>
                            <td>
                                {{formatDate(content.published_date)}}
                            </td>
                            <td class="text-center">
                                <i class="cursor-pointer la la-lg la-pencil text-info"
                                    title="Cập nhật"
                                    @click="openUpdateForm(content)"></i>

                                <i class="cursor-pointer la la-lg la-trash text-danger ml-2"
                                    title="Xóa"
                                    @click="deleteRecord(content)"></i>

                                <i class="cursor-pointer la la-lg la-arrow-right text-success ml-2"
                                    title="Xuất bản"
                                    @click="changeStatus(content, 2)"
                                    v-if="content.status == 1"></i>
                                <i class="cursor-pointer la la-lg la-arrow-left text-warning ml-2"
                                    title="Chuyển về nháp"
                                    @click="changeStatus(content, 1)"
                                    v-else></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Form thêm mới, cập nhật -->
        <div v-show="screen == 'form'">
            <content-form ref="contentForm"
                @stored="search()"
                @close="screen = 'list'"
                :status-list="statusList"
                :type-list="typeList" />
        </div>
    </div>
</template>


<script>
import ContentForm from './ContentForm.vue';

export default {
    components: {
        ContentForm
    },

    data() {
        return {
            // Danh sách kết quả tìm kiếm
            contentList: [],

            // Text tìm kiếm
            searchText: '',

            // Trạng thái
            status: {},
            statusList: [
                {
                    id: 1,
                    name: 'Nháp'
                },
                {
                    id: 2,
                    name: 'Đã xuất bản'
                }
            ],
            type: {},
            typeList: [
                {
                    id: 1,
                    name: 'Tin tức'
                },
                {
                    id: 2,
                    name: 'Page'
                }
            ],

            // Đối tượng datatable
            datatable: null,

            // Màn hình danh sách (list) hay màn hình form
            screen: 'list'
        };
    },

    mounted() {
        this.initDatatable();
    },

    methods: {
        /**
         * Lấy các tham số khi gọi API.
         */
        getParams() {
            return {
                search: this.searchText,
                status: this.status.id,
                type: this.type.id
            };
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
                    return axios.get('/content/search', { params });
                },
                bindItemsCallback: (items) => {
                    this.contentList = items;
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
         * Bật form thêm mới.
         */
        openCreateForm() {
            this.screen = 'form';
        },

        /**
         * Bật form cập nhật.
         */
        async openUpdateForm(content) {
            this.screen = 'form';

            // Đối tượng content ở đầu vào đang chưa có trường content
            // Gọi thêm API để lấy đầy đủ dữ liệu
            const { data } = await axios.get('/content/' + content.id);
            this.$refs.contentForm.openUpdateForm(data);
        },

        /**
         * Xóa bản ghi.
         */
        deleteRecord(content) {
            noti.confirm('Bạn chắc chắn muốn xoá bản ghi <b>' + CommonUtils.escapeHtml(content.title) + '</b> hay không?', async () => {
                const { data } = await axios.delete('/content/destroy/' + content.id);
                if (data.code == 0) {
                    noti.success('Xóa thành công');
                    this.search();
                }
            });
        },

        /**
         * Xuất bản, chuyển về nháp.
         */
        changeStatus(content, status) {
            const action = (status == 1) ? 'chuyển về nháp' : 'xuất bản';

            noti.confirm(`Bạn chắc chắn muốn ${action} bản ghi <b>${CommonUtils.escapeHtml(content.title)}</b> hay không?`, async () => {
                const params = {
                    status
                };
                const { data } = await axios.post('/content/change-status/' + content.id, params);
                if (data.code == 0) {
                    noti.success('Cập nhật thành công');
                    this.search();
                } else {
                    noti.error('Đã có lỗi xảy ra');
                }
            });
        },

        /**
         * Tên loại nội dung.
         */
        getTypeName(type) {
            const obj = this.typeList.find(e => e.id == type);
            return obj.name;
        },

        /**
         * Tên trạng thái.
         */
        getStatusName(status) {
            const obj = this.statusList.find(e => e.id == status);
            return obj.name;
        }
    }
};
</script>
