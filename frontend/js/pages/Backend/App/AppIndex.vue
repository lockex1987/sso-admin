<template>
    <div>
        <top-header :paths="['Quản lý ứng dụng']" />

        <app-list />
    </div>
</template>


<script>
import AppList from './components/AppList.vue';

export default {
    components: {
        AppList
    }
};
</script>
