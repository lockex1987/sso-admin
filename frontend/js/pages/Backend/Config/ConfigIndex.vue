<template>
    <div>
        <top-header :paths="['Cấu hình hệ thống']" />

        <config-list />
    </div>
</template>


<script>
import ConfigList from './components/ConfigList.vue';

export default {
    components: {
        ConfigList
    }
};
</script>
