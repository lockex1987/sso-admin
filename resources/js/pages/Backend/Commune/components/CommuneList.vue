<template>
    <div>
        <div class="d-flex flex-wrap mt-5 mb-2">
            <input type="text"
                    class="form-control"
                    v-model.trim="searchText"
                    placeholder="Mã hoặc tên"
                    @input="debouncedSearch()"
                    style="width: 200px;">

            <single-select
                    class="ml-2"
                    placeholder="Loại"
                    :options="typeList"
                    v-model="type"
                    :show-clear="true"
                    @change="search()"
                    style="width: 250px;"/>

            <div class="border rounded d-flex ml-2 cascade-select">
                <single-select
                        class=""
                        placeholder="Tỉnh / thành phố"
                        :options="provinceList"
                        v-model="province"
                        :show-clear="!district.id"
                        :has-search="true"
                        @change="updateDistrictSelect(); search();"/>

                <single-select
                        class="ml-2"
                        placeholder="Huyện / quận"
                        :options="districtList"
                        v-model="district"
                        :show-clear="!commune.id"
                        :has-search="true"
                        @change="updateCommuneSelect(); search()"
                        v-show="districtList.length > 0"/>

                <single-select
                        class="ml-2"
                        placeholder="Xã / phường"
                        :options="communeList"
                        v-model="commune"
                        :show-clear="true"
                        :has-search="true"
                        @change="search()"
                        v-show="communeList.length > 0"/>
            </div>

            <button class="btn btn-primary btn-ripple ml-auto"
                    type="button"
                    @click="openCreateForm()">
                Thêm mới
            </button>

            <button type="button"
                    class="btn btn-secondary ml-2"
                    @click="exportExcel()">
                Export
            </button>

            <import-button
                    :validate-row="validateRow"
                    :insert-row="insertRow"
                    :is-data-row="isDataRow"
                    :template-path="'/templates/Danh sách cấp xã ___15_07_2020.xlsx'"
                    @done="search()"/>

            <button type="button"
                    class="btn btn-danger ml-2"
                    @click="deleteAll()">
                Xóa danh sách
            </button>
        </div>

        <div class="datatable-wrapper">
            <table class="table table-bordered"
                    ref="searchResult"
                    v-show="resultList.length > 0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px">
                            #
                        </th>
                        <th class="text-center">
                            Mã
                        </th>
                        <th class="text-center">
                            Tên
                        </th>
                        <th class="text-center">
                            Loại
                        </th>
                        <th class="text-center">
                            Tỉnh / thành phố
                        </th>
                        <th class="text-center">
                            Huyện / quận
                        </th>
                        <th class="text-center" style="width: 215px;">
                            Thao tác
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="commune in resultList"
                            :key="commune.id">
                        <td class="text-center">
                            {{commune.stt}}
                        </td>
                        <td>
                            {{commune.code}}
                        </td>
                        <td>
                            {{commune.name}}
                        </td>
                        <td>
                            {{getTypeName(commune.type)}}
                        </td>
                        <td>
                            {{commune.province.name}}
                        </td>
                        <td>
                            {{commune.district.name}}
                        </td>
                        <td class="text-center">
                            <i class="cursor-pointer la la-lg la-pencil text-info mr-2"
                                    title="Cập nhật"
                                    @click="openUpdateForm(commune)"></i>

                            <i class="cursor-pointer la la-lg la-trash text-danger mr-2"
                                    title="Xóa"
                                    @click="deleteRecord(commune)"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <commune-form
                ref="communeForm"
                :type-list="typeList"
                :province-list="provinceList"
                @stored="search()"/>
    </div>
</template>


<script>
import CommuneForm from './CommuneForm.vue';

export default {
    components: {
        CommuneForm
    },

    data() {
        return {
            // Danh sách kết quả tìm kiếm
            resultList: [],

            // Text tìm kiếm
            searchText: '',

            // Đối tượng datatable
            datatable: null,

            type: {},
            typeList: [
                { id: 1, name: 'Xã' },
                { id: 2, name: 'Phường' },
                { id: 3, name: 'Thị trấn' }
            ],

            province: {},
            // Danh sách tỉnh thành phố
            provinceList: [],
            district: {},
            districtList: [],
            commune: {},
            communeList: []
        };
    },

    mounted() {
        this.initDatatable();
        this.getProvinceList();
    },

    methods: {
        async getProvinceList() {
            const { data } = await axios.get('/province/search');
            this.provinceList = data;
        },

        async getDistrictList() {
            const params = {
                provinceId: this.province.id
            };
            const { data } = await axios.get('/district/search', { params });
            this.districtList = data;
        },

        async getCommuneList() {
            const params = {
                districtId: this.district.id
            };
            const { data } = await axios.get('/commune/search', { params });
            this.communeList = data;
        },

        updateDistrictSelect() {
            this.district = {};

            this.updateCommuneSelect();

            if (this.province.id) {
                this.getDistrictList();
            } else {
                this.districtList = [];
            }
        },

        updateCommuneSelect() {
            this.commune = {};
            if (this.district.id) {
                this.getCommuneList();
            } else {
                this.communeList = [];
            }
        },

        /**
         * Lấy các tham số.
         * Dùng ở tìm kiếm, xuất Excel, xóa danh sách.
         */
        getParams() {
            return {
                search: this.searchText,
                type: this.type.id,
                provinceId: this.province.id,
                districtId: this.district.id
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
                    return axios.get('/commune/search', { params });
                },
                bindItemsCallback: (items) => {
                    this.resultList = items;
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
            this.$refs.communeForm.openCreateForm();
        },

        /**
         * Bật form cập nhật.
         */
        openUpdateForm(commune) {
            this.$refs.communeForm.openUpdateForm(commune);
        },

        /**
         * Xóa bản ghi.
         */
        deleteRecord(commune) {
            noti.confirm('Bạn chắc chắn muốn xoá bản ghi <b>' + CommonUtils.escapeHtml(commune.name) + '</b> hay không?', async () => {
                const { data } = await axios.delete('/commune/destroy/' + commune.id);
                if (data.code == 0) {
                    noti.success('Xóa thành công');
                    this.search();
                } else if (data.code == 2) {
                    noti.error(data.message);
                }
            });
        },

        /**
         * Xóa toàn bộ danh sách tìm thấy.
         */
        deleteAll() {
            noti.confirm('Bạn chắc chắn muốn xoá toàn bộ danh sách tìm thấy?', async () => {
                const params = this.getParams();
                const { data } = await axios.delete('/commune/destroy-multiple', { data: params });
                noti.success('Xóa thành công ' + CommonUtils.escapeHtml(data.num) + ' bản ghi');
                this.search();
            });
        },

        /**
         * Xuất dữ liệu Excel.
         */
        async exportExcel() {
            // Lấy dữ liệu từ server
            const params = this.getParams();
            const { data } = await axios.get('/commune/search', { params });
            const list = data;
            if (list.length == 0) {
                noti.warning('Không có dữ liệu');
                return;
            }

            list.forEach(e => {
                e.typeName = this.getTypeName(e.type);
            });

            const columns = [
                {
                    header: 'Mã',
                    key: 'code',
                    width: 20
                },
                {
                    header: 'Tên',
                    key: 'name',
                    width: 30
                },
                {
                    header: 'Loại',
                    key: 'typeName',
                    width: 30
                }
            ];
            const sheetName = 'Commune';
            const fileName = 'tinh.xlsx';

            this.exportExcelCommon(list, columns, sheetName, fileName);
        },

        /**
         * Kiểm tra dữ liệu của dòng.
         */
        validateRow(rowData) {
            const code = rowData[0];
            const name = rowData[1];
            const type = rowData[3];
            const districtCode = rowData[4];

            // Validate dữ liệu ở client
            const validateErrors = [];

            const validatecode = CV.validateByValueAndValidation(code, CV.parseValidationFromString('required|maxLength:100'));
            if (validatecode) {
                validateErrors.push('<b>Mã</b>: ' + validatecode);
            }

            const validateName = CV.validateByValueAndValidation(name, CV.parseValidationFromString('required|maxLength:100'));
            if (validateName) {
                validateErrors.push('<b>Tên</b>: ' + validateName);
            }

            const typeNames = this.typeList.map(e => e.name).join(',');
            const validateType = CV.validateByValueAndValidation(type, CV.parseValidationFromString('required|in:' + typeNames));
            if (validateType) {
                validateErrors.push('<b>Loại</b>: ' + validateType);
            } else {
                const typeObj = this.typeList.find(e => e.name.toLowerCase() == type.toLowerCase());
                rowData[3] = typeObj.id;
            }

            if (!districtCode) {
                validateErrors.push('<b>Mã thành phố</b>: Vui lòng nhập bản ghi này');
            }

            return validateErrors;
        },

        /**
         * Thêm mới dòng dữ liệu.
         */
        insertRow(rowData) {
            const code = rowData[0];
            const name = rowData[1];
            const type = rowData[3];
            const districtCode = rowData[4];

            // Gọi lên server
            const params = {
                code,
                name,
                type,
                districtCode
            };
            return axios.post('/commune/store', params);
        },

        /**
         * Dòng Excel có chứa dữ liệu hay không.
         */
        isDataRow(rowData) {
            const code = rowData[0];
            return !!code;
        },

        /**
         * Lấy loại: Tỉnh / Thành phố Trung ương.
         */
        getTypeName(type) {
            const typeObj = this.typeList.find(e => e.id == type);
            return typeObj.name;
        }
    }
};
</script>


<style lang="scss">
// https://github.com/yesilfasulye/AutoCompleTree
.cascade-select {
    .custom-select {
        border: none;
        height: calc(1.5em + .75rem);
    }

    .dropdown-menu {
        min-width: 200px;
    }
}
</style>
