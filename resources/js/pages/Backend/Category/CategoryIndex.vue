<template>
    <div>
        <the-breadcrumb :paths="[categoryObj.name]"/>

        <category-list
                ref="categoryList"
                :category-obj="categoryObj"/>
    </div>
</template>


<script>
import CategoryList from './components/CategoryList.vue';

export default {
    components: {
        CategoryList
    },

    data() {
        return {
            // Loại (movie_format, movie_genre,...)
            categoryObj: {}
        };
    },

    watch: {
        async $route(to, from) {
            // React to route changes
            await this.getInfo();

            // Chờ cho categoryObj được áp dụng
            this.$nextTick(() => {
                this.$refs.categoryList.resetForm();
                this.$refs.categoryList.search();
            });
        }
    },

    async mounted() {
        // Lấy thông tin
        await this.getInfo();

        // Chờ cho categoryObj được áp dụng
        this.$nextTick(() => {
            this.$refs.categoryList.initDatatable();
        });
    },

    methods: {
        /**
         * Lấy thông tin.
         */
        async getInfo() {
            const categoryId = this.$route.params.category;
            const { data } = await axios.get('/category/' + categoryId);
            this.categoryObj = data;
        }
    }
};
</script>
