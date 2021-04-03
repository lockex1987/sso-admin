<template>
    <div>
        <the-breadcrumb :paths="['Dashboard']"/>

        <div class="contaner-fluid">
            <div class="row">
                <div class="col-lg-6 col-xl-3 mb-2">
                    <div class="h-100 p-4 bg-primary text-white rounded d-flex align-items-center">
                        <i class="la la-user font-size-3 mr-lg-5"></i>
                        <div>
                            <span class="d-block font-size-2">{{stats.user}}</span>
                            <span class="text-uppercase">người dùng</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-xl-3 mb-2">
                    <div class="h-100 p-4 bg-danger text-white rounded d-flex align-items-center">
                        <i class="lab la-app-store font-size-3 mr-lg-5"></i>
                        <div>
                            <span class="d-block font-size-2">{{stats.app}}</span>
                            <span class="text-uppercase">ứng dụng</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-xl-3 mb-2">
                    <div class="h-100 p-4 bg-warning text-white rounded d-flex align-items-center">
                        <i class="la la-shield-alt font-size-3 mr-lg-5"></i>
                        <div>
                            <span class="d-block font-size-2">{{stats.permission}}</span>
                            <span class="text-uppercase">quyền</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-xl-3 mb-2">
                    <div class="h-100 p-4 bg-info text-white rounded d-flex align-items-center">
                        <i class="la la-users font-size-3 mr-lg-5"></i>
                        <div>
                            <span class="d-block font-size-2">{{stats.role}}</span>
                            <span class="text-uppercase">vai trò</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    data() {
        return {
            // Các thông tin lọc
            stats: {}
        };
    },

    mounted() {
        this.getStats();
    },

    methods: {
        /**
         * Lấy dữ liệu thống kê.
         */
        async getStats() {
            const { data } = await axios.get('/dashboard/stats');
            this.stats = data;
        }
    }
};
</script>
