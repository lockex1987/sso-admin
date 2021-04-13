<template>
    <div>
        <div class="form-inline flex-wrap">
            <input type="text"
                class="form-control mb-2"
                v-model.trim="searchText"
                placeholder="Từ khóa"
                @input="debouncedSearch()">

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
                :template-path="'/templates/country.xlsx'"
                class="mb-2"
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
                v-show="countryList.length > 0">
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
                    <tr v-for="country in countryList"
                        :key="country.id">
                        <td class="text-center">
                            {{country.stt}}
                        </td>
                        <td>
                            {{country.code}}
                        </td>
                        <td>
                            {{country.name}}
                        </td>
                        <td class="text-center">
                            <i class="cursor-pointer la la-lg la-pencil text-info mr-2"
                                title="Cập nhật"
                                @click="openUpdateForm(country)"></i>

                            <i class="cursor-pointer la la-lg la-trash text-danger mr-2"
                                title="Xóa"
                                @click="deleteRecord(country)"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <country-form ref="countryForm"
            @stored="search()" />
    </div>
</template>


<script>
import CountryForm from './CountryForm.vue';

export default {
    components: {
        CountryForm
    },

    data() {
        return {
            // Danh sách kết quả tìm kiếm
            countryList: [],

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
         * Lấy các tham số.
         * Dùng ở tìm kiếm, xuất Excel, xóa danh sách.
         */
        getParams() {
            return {
                search: this.searchText
            };
        },

        /**
         * Khởi tạo đối tượng datatable.
         */
        initDatatable() {
            this.datatable = new Datatable({
                table: this.$refs.searchResult,
                ajax: (page, pageSize, sortColumn, sortDirection) => {
                    const params = {
                        ...this.getParams(),
                        page: page - 1,
                        size: pageSize
                    };
                    return axios.get('/country/search', { params });
                },
                bindItemsCallback: (items) => {
                    this.countryList = items;
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
            this.$refs.countryForm.openCreateForm();
        },

        /**
         * Bật form cập nhật.
         */
        openUpdateForm(country) {
            this.$refs.countryForm.openUpdateForm(country);
        },

        /**
         * Xóa bản ghi.
         */
        deleteRecord(country) {
            noti.confirm('Bạn chắc chắn muốn xoá bản ghi <b>' + CommonUtils.escapeHtml(country.code) + '</b> hay không?', async () => {
                const { data } = await axios.delete('/country/destroy/' + country.id);
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
                const { data } = await axios.delete('/country/destroy-multiple', { data: params });
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
            const { data } = await axios.get('/country/search', { params });
            const list = data;
            if (list.length == 0) {
                noti.warning('Không có dữ liệu');
                return;
            }

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
                }
            ];
            const sheetName = 'Country';
            const fileName = 'quoc gia.xlsx';

            this.exportExcelCommon(list, columns, sheetName, fileName);
        },

        /**
         * Kiểm tra dữ liệu của dòng.
         */
        validateRow(rowData) {
            const code = rowData[0];
            const name = rowData[1];

            // Validate dữ liệu ở client
            const validateErrors = [];

            const validateCode = CV.validateByValueAndValidation(code, CV.parseValidationFromString('required|maxLength:100'));
            if (validateCode) {
                validateErrors.push('<b>Mã</b>: ' + validateCode);
            }

            const validateName = CV.validateByValueAndValidation(name, CV.parseValidationFromString('required|maxLength:100'));
            if (validateName) {
                validateErrors.push('<b>Tên</b>: ' + validateName);
            }

            return validateErrors;
        },

        /**
         * Thêm mới dòng dữ liệu.
         */
        insertRow(rowData) {
            const code = rowData[0];
            const name = rowData[1];

            // Gọi lên server
            const params = {
                code: code,
                name: name
            };
            return axios.post('/country/store', params);
        }
    }
};
</script>
