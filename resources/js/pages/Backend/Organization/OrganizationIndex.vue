<template>
    <div class="container-fluid">
        <div class="bg-white rounded p-3 backend-layout-min-height">
            <organization-list />
        </div>
    </div>
</template>


<script>
import OrganizationList from './components/OrganizationList.vue';

export default {
    components: {
        OrganizationList
    }
};
</script>
