<template>
    <div>
        <div class="form-inline">
            <input type="text"
                class="form-control mb-2 mr-sm-2"
                v-model.trim="searchText"
                placeholder="Từ khóa"
                @input="debouncedSearch()">

            <button class="btn btn-primary btn-ripple mb-2 ml-auto"
                type="button"
                @click="openCreateForm()">
                Thêm mới
            </button>
        </div>

        <table class="table table-bordered"
            v-show="configList.length > 0">
            <thead>
                <tr>
                    <th class="text-center"
                        style="width: 50px">
                        #
                    </th>
                    <th class="text-center">
                        Mã
                    </th>
                    <th class="text-center">
                        Tên
                    </th>
                    <th class="text-center">
                        Giá trị
                    </th>
                    <th class="text-center"
                        style="width: 215px;">
                        Thao tác
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(config, i) in configList"
                    :key="config.id">
                    <td class="text-center">
                        {{pagi.from + i}}
                    </td>
                    <td>
                        {{config.code}}
                    </td>
                    <td>
                        {{config.name}}
                    </td>
                    <td>
                        {{config.value}}
                    </td>
                    <td class="text-center">
                        <i class="cursor-pointer la la-lg la-pencil text-info mr-2"
                            title="Cập nhật"
                            @click="openUpdateForm(config)"></i>

                        <i class="cursor-pointer la la-lg la-trash text-danger mr-2"
                            title="Xóa"
                            @click="deleteRecord(config)"
                            v-if="!config.strict"></i>
                    </td>
                </tr>
            </tbody>
        </table>

        <pagi @change="search"
            v-model="pagi"></pagi>

        <config-form ref="systemConfigForm"
            @stored="search()" />
    </div>
</template>


<script>
import ConfigForm from './ConfigForm.vue';

export default {
    components: {
        ConfigForm
    },

    data() {
        return {
            // Danh sách kết quả tìm kiếm
            configList: [],

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
            const { data } = await axios.get('/config/search', { params });
            this.pagi = data;
            this.configList = data.data;
        },

        /**
         * Bật form thêm mới.
         */
        openCreateForm() {
            this.$refs.systemConfigForm.openCreateForm();
        },

        /**
         * Bật form cập nhật.
         */
        openUpdateForm(config) {
            this.$refs.systemConfigForm.openUpdateForm(config);
        },

        /**
         * Xóa bản ghi.
         */
        deleteRecord(config) {
            noti.confirm('Bạn chắc chắn muốn xoá bản ghi <b>' + CommonUtils.escapeHtml(config.code) + '</b> hay không?', async () => {
                const { data } = await axios.delete('/config/destroy/' + config.id);
                if (data.code == 0) {
                    noti.success('Xóa thành công');
                    this.search();
                } else if (data.code == 2) {
                    noti.error(data.message);
                }
            });
        }
    }
};
</script>
