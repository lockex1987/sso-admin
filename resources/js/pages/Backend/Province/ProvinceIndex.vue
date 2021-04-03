<template>
    <div>
        <the-breadcrumb :paths="['Tỉnh / thành phố']"/>

        <province-list />
    </div>
</template>


<script>
import ProvinceList from './components/ProvinceList.vue';

export default {
    components: {
        ProvinceList
    }
};
</script>
