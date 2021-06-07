<template>
    <div class="d-flex flex-column h-100">
        <div class="flex-1 custom-scrollbar overflow-auto">
            <div class="tree tree--icon tree--selectable mb-3">
                <tree :data="allList"
                        :selected-node="selectedOrg"
                        @click="selectOrganization($event)" />
            </div>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        // Đơn vị đang được chọn
        selectedOrg: {
            type: Object,
            default: {}
        },

        // Danh sách tất cả tổ chức
        allList: {
            type: Array,
            default: []
        }
    },

    methods: {
        /**
         * Chọn một đơn vị nào đó.
         */
        selectOrganization(org) {
            this.$emit('selected', org);
        }
    }
};
</script>
