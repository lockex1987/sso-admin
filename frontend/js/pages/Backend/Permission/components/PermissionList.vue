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

        <table class="table table-bordered table-responsive-md"
            v-show="permissionList.length > 0">
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
                    <th class="text-center"
                        style="width: 215px;">
                        Thao tác
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(permission, i) in permissionList"
                    :key="permission.id">
                    <td class="text-center">
                        {{pagi.from + i}}
                    </td>
                    <td>
                        {{permission.code}}
                    </td>
                    <td>
                        {{permission.name}}
                    </td>
                    <td class="text-center">
                        <i class="cursor-pointer la la-lg la-pencil text-info mr-2"
                            title="Cập nhật"
                            @click="openUpdateForm(permission)"></i>

                        <i class="cursor-pointer la la-lg la-trash text-danger mr-2"
                            title="Xóa"
                            @click="deleteRecord(permission)"></i>
                    </td>
                </tr>
            </tbody>
        </table>

        <pagi @change="search"
            v-model="pagi" />

        <permission-form ref="appForm"
            @created="search(1)"
            @updated="search(pagi.current_page)" />
    </div>
</template>


<script>
import PermissionForm from './PermissionForm.vue';

export default {
    components: {
        PermissionForm
    },

    data() {
        return {
            // Danh sách kết quả tìm kiếm
            permissionList: [],

            // Text tìm kiếm
            searchText: '',

            // Đối tượng phân trang
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
            const { data } = await axios.get('/permission/search', { params });
            this.pagi = data;
            this.permissionList = data.data;
        },

        /**
         * Bật form thêm mới.
         */
        openCreateForm() {
            this.$refs.appForm.openCreateForm();
        },

        /**
         * Bật form cập nhật.
         */
        openUpdateForm(permission) {
            this.$refs.appForm.openUpdateForm(permission);
        },

        /**
         * Xóa bản ghi.
         */
        deleteRecord(permission) {
            const message = 'Bạn chắc chắn muốn xoá bản ghi <b>' + CommonUtils.escapeHtml(permission.code) + '</b> hay không?';
            noti.confirm(message, async () => {
                const params = {
                    id: permission.id
                };
                const { data } = await axios.delete('/permission/destroy', { data: params });
                if (data.code == 0) {
                    noti.success('Xóa thành công');
                    const page = Math.max(1, (this.permissionList.length == 1) ? this.pagi.current_page - 1 : this.pagi.current_page);
                    this.search(page);
                } else {
                    noti.error(data.message);
                }
            });
        }
    }
};
</script>
