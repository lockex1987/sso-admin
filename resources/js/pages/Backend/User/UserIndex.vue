<template>
    <div>
        <top-header :paths="['Quản trị người dùng']" />

        <user-list />
    </div>
</template>


<script>
import UserList from './components/UserList.vue';

export default {
    components: {
        UserList
    }
};
</script>
