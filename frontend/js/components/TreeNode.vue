<template>
    <li class="tree-node">
        <span class="tree__expander"
                :class="{ 'expanded': isExpanded }"
                @click="toggleChildren()"
                v-if="children && children.length"></span>
        <span class="tree__leaf"
                v-else></span>

        <span class="tree__text"
                @click="triggerPropagateClick()"
                :class="{ 'tree__text--selected': node.id == selectedNode.id }">
            {{node.name}}
        </span>

        <ul v-if="children && children.length"
                :class="{ 'expanded': isExpanded }">
            <tree-node
                    v-for="child in children"
                    :node="child"
                    :data="data"
                    :selected-node="selectedNode"
                    @click="propagateClick($event)"
                    :key="child.id" />
        </ul>
    </li>
</template>


<script>
export default {
    // Phải thêm cái này vì chưa định nghĩa toàn cục
    // Vue.component('tree-node', TreeNode);
    name: 'tree-node',

    props: [
        'data',
        'selectedNode',
        'node'
    ],

    data() {
        return {
            isExpanded: false
        };
    },

    computed: {
        /**
         * Lấy danh sách dữ liệu con của một node nào đó.
         */
        children() {
            // ID có thể null trong trường hợp có tùy chọn là "Chọn tất cả"
            return this.data.filter(child => child.parentId != null && child.parentId == this.node.id);
        }
    },

    methods: {
        toggleChildren() {
            this.isExpanded = !this.isExpanded;
        },

        triggerPropagateClick() {
            this.$emit('click', this.node);
        },

        propagateClick(node) {
            this.$emit('click', node);
        }
    }
};
</script>
