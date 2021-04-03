<template>
    <div>
        <div class="d-flex mt-5 mb-2">
            <single-select
                    placeholder="Trạng thái"
                    :options="statusList"
                    v-model="status"
                    :show-clear="true"
                    @change="search()"
                    style="width: 250px;"/>

            <button class="btn btn-primary btn-ripple ml-auto"
                    type="button"
                    @click="openCreateForm()">
                Thêm mới
            </button>
        </div>

        <div class="datatable-wrapper">
            <table class="table table-bordered"
                    ref="searchResult"
                    v-show="slideList.length > 0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px">
                            #
                        </th>
                        <th class="text-center">
                            Preview
                        </th>
                        <th class="text-center">
                            URL
                        </th>
                        <th class="text-center">
                            Trạng thái
                        </th>
                        <th class="text-center" style="width: 215px;">
                            Thao tác
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="slide in slideList"
                            :key="slide.id">
                        <td class="text-center">
                            {{slide.stt}}
                        </td>
                        <td>
                            <img src="/images/placeholder-image.png"
                                    class="preview object-fit-cover"/>
                        </td>
                        <td>
                            {{slide.url}}
                        </td>
                        <td>
                            {{getTypeName(slide.is_active)}}
                        </td>
                        <td class="text-center">
                            <i class="cursor-pointer la la-lg la-lock text-warning"
                                    title="Khóa lại"
                                    @click="changeStatus(slide)"
                                    v-if="slide.is_active == 1"></i>

                            <i class="cursor-pointer la la-lg la-unlock text-warning"
                                    title="Mở khóa"
                                    @click="changeStatus(slide)"
                                    v-else></i>

                            <i class="cursor-pointer la la-lg la-trash text-danger ml-2"
                                    title="Xóa"
                                    @click="deleteRecord(slide)"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <slide-form
                ref="slideForm"
                @stored="search()"/>
    </div>
</template>


<script>
import SlideForm from './SlideForm.vue';

export default {
    components: {
        SlideForm
    },

    data() {
        return {
            // Danh sách kết quả tìm kiếm
            slideList: [],

            // Đối tượng datatable
            datatable: null,

            status: {},
            statusList: [
                { id: 1, name: 'Đang hoạt động' },
                { id: 0, name: 'Không hoạt động' }
            ]
        };
    },

    mounted() {
        this.initDatatable();
    },

    methods: {
        /**
         * Lấy các tham số.
         * Dùng ở tìm kiếm, xuất Excel, xóa danh sách.
         */
        getParams() {
            return {
                status: this.status.id
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
                    return axios.get('/slide/search', { params });
                },
                bindItemsCallback: (items) => {
                    this.slideList = items;
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
         * Tìm kiếm.
         */
        search() {
            this.datatable.reload();
        },

        /**
         * Bật form thêm mới.
         */
        openCreateForm() {
            this.$refs.slideForm.openCreateForm();
        },

        /**
         * Xóa bản ghi.
         */
        deleteRecord(slide) {
            noti.confirm('Bạn chắc chắn muốn xoá bản ghi <b>' + CommonUtils.escapeHtml(slide.name) + '</b> hay không?', async () => {
                const { data } = await axios.delete('/slide/destroy/' + slide.id);
                if (data.code == 0) {
                    noti.success('Xóa thành công');
                    this.search();
                } else if (data.code == 2) {
                    noti.error(data.message);
                }
            });
        },

        /**
         * Lấy loại: Tỉnh / Thành phố Trung ương.
         */
        getTypeName(status) {
            const statusObj = this.statusList.find(e => e.id == status);
            return statusObj.name;
        },

        /**
         * Đổi trạng thái.
         */
        changeStatus(slide) {
            const action = (slide.is_active == 1) ? 'khóa' : 'mở khóa';
            noti.confirm(`Bạn chắc chắn muốn ${action} slide hay không?`, async () => {
                const params = {
                    id: slide.id,
                    isActive: (slide.is_active == 1) ? 0 : 1
                };
                const { data } = await axios.post('/slide/update-is-active', params);
                if (data.code == 0) {
                    noti.success('Cập nhật thành công');
                    this.search();
                }
            });
        }
    }
};
</script>


<style lang="scss" scoped>
.preview {
    width: 16rem;
    height: 9rem;
}
</style>
