<template>
    <div>
        <the-breadcrumb :paths="['Quản lý vai trò']"/>

        <role-list />
    </div>
</template>


<script>
import RoleList from './components/RoleList.vue';

export default {
    components: {
        RoleList
    }
};
</script>
