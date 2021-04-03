<template>
    <div>
        <the-breadcrumb :paths="['Slide']"/>

        <slide-list />
    </div>
</template>


<script>
import SlideList from './components/SlideList.vue';

export default {
    components: {
        SlideList
    }
};
</script>
