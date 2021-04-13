<template>
    <div class="d-md-flex">
        <left-aside />

        <div class="flex-grow-1 overflow-y-auto overflow-x-hidden custom-scrollbar px-3 pb-3 vh-100">
            <router-view />
        </div>
    </div>
</template>


<script>
import LeftAside from './components/LeftAside.vue';

export default {
    components: {
        LeftAside
    }
};
</script>

<style lang="scss">
// Để hiển thị full chiều cao
.backend-layout-min-height {
    // Trừ đi chiều cao của top-header và padding-bottom
    min-height: calc(100vh - 70px - 1rem);
}
</style>
