<template>
    <div>
        <div class="d-flex flex-wrap">
            <input type="text"
                class="form-control mb-2"
                v-model.trim="searchText"
                placeholder="Mã hoặc tên"
                @input="debouncedSearch()"
                style="width: 200px;">

            <single-select class="ml-2 mb-2"
                placeholder="Loại"
                :options="typeList"
                v-model="type"
                :show-clear="true"
                @change="search()"
                style="width: 250px;" />

            <button class="btn btn-primary btn-ripple ml-auto mb-2"
                type="button"
                @click="openCreateForm()">
                Thêm mới
            </button>

            <button type="button"
                class="btn btn-secondary ml-2 mb-2"
                @click="exportExcel()">
                Export
            </button>

            <import-button :validate-row="validateRow"
                :insert-row="insertRow"
                :is-data-row="isDataRow"
                :template-path="'/templates/Danh sách cấp tỉnh __15_07_2020.xlsx'"
                @done="search()" />

            <button type="button"
                class="btn btn-danger ml-2 mb-2"
                @click="deleteAll()">
                Xóa danh sách
            </button>
        </div>

        <div class="datatable-wrapper">
            <table class="table table-bordered"
                ref="searchResult"
                v-show="provinceList.length > 0">
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
                            Loại
                        </th>
                        <th class="text-center"
                            style="width: 215px;">
                            Thao tác
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="province in provinceList"
                        :key="province.id">
                        <td class="text-center">
                            {{province.stt}}
                        </td>
                        <td>
                            {{province.code}}
                        </td>
                        <td>
                            {{province.name}}
                        </td>
                        <td>
                            {{getTypeName(province.type)}}
                        </td>
                        <td class="text-center">
                            <i class="cursor-pointer la la-lg la-pencil text-info mr-2"
                                title="Cập nhật"
                                @click="openUpdateForm(province)"></i>

                            <i class="cursor-pointer la la-lg la-trash text-danger mr-2"
                                title="Xóa"
                                @click="deleteRecord(province)"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <province-form ref="provinceForm"
            :type-list="typeList"
            @stored="search()" />
    </div>
</template>


<script>
import ProvinceForm from './ProvinceForm.vue';

export default {
    components: {
        ProvinceForm
    },

    data() {
        return {
            // Danh sách kết quả tìm kiếm
            provinceList: [],

            // Text tìm kiếm
            searchText: '',

            // Đối tượng datatable
            datatable: null,

            type: {},
            typeList: [
                { id: 1, name: 'Tỉnh' },
                { id: 2, name: 'Thành phố Trung ương' }
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
                search: this.searchText,
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
                    return axios.get('/province/search', { params });
                },
                bindItemsCallback: (items) => {
                    this.provinceList = items;
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
            this.$refs.provinceForm.openCreateForm();
        },

        /**
         * Bật form cập nhật.
         */
        openUpdateForm(province) {
            this.$refs.provinceForm.openUpdateForm(province);
        },

        /**
         * Xóa bản ghi.
         */
        deleteRecord(province) {
            noti.confirm('Bạn chắc chắn muốn xoá bản ghi <b>' + CommonUtils.escapeHtml(province.name) + '</b> hay không?', async () => {
                const { data } = await axios.delete('/province/destroy/' + province.id);
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
                const { data } = await axios.delete('/province/destroy-multiple', { data: params });
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
            const { data } = await axios.get('/province/search', { params });
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
            const sheetName = 'Province';
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
                const typeObj = this.typeList.find(e => e.name == type);
                rowData[3] = typeObj.id;
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

            // Gọi lên server
            const params = {
                code,
                name,
                type
            };
            return axios.post('/province/store', params);
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
