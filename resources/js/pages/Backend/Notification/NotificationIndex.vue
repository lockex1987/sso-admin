<template>
    <div>
        <the-breadcrumb :paths="['Thông báo']"/>

        <notification-list />
    </div>
</template>


<script>
import NotificationList from './components/NotificationList.vue';

export default {
    components: {
        NotificationList
    }
};
</script>
