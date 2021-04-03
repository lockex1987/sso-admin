<template>
    <div class="container-fluid">
        <div class="bg-white rounded p-3 backend-layout-min-height">
            <content-list />
        </div>
    </div>
</template>


<script>
import ContentList from './components/ContentList.vue';

export default {
    components: {
        ContentList
    }
};
</script>
