<template>
    <div>
        <top-header :paths="['Huyện / quận']" />

        <district-list />
    </div>
</template>


<script>
import DistrictList from './components/DistrictList.vue';

export default {
    components: {
        DistrictList
    }
};
</script>
