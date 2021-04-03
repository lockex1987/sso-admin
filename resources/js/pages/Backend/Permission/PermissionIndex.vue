<template>
    <div>
        <the-breadcrumb :paths="['Quản lý quyền']"/>

        <permission-list />
    </div>
</template>


<script>
import PermissionList from './components/PermissionList.vue';

export default {
    components: {
        PermissionList
    }
};
</script>
