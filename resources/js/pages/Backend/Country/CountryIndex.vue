<template>
    <div>
        <the-breadcrumb :paths="['Quốc gia']"/>

        <country-list />
    </div>
</template>


<script>
import CountryList from './components/CountryList.vue';

export default {
    components: {
        CountryList
    }
};
</script>
