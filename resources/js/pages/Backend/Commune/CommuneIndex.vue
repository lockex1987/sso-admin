<template>
    <div>
        <the-breadcrumb :paths="['Xã / phường']"/>

        <commune-list />
    </div>
</template>


<script>
import CommuneList from './components/CommuneList.vue';

export default {
    components: {
        CommuneList
    }
};
</script>
