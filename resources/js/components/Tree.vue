<template>
    <ul class="expanded">
        <tree-node
                v-for="firstChild in rootList"
                :node="firstChild"
                :data="data"
                :selected-node="selectedNode"
                @click="propagateClick($event)"
                :key="firstChild.id" />
    </ul>
</template>


<script>
import TreeNode from './TreeNode.vue';

export default {
    components: { TreeNode },

    props: [
        'data',
        'selectedNode'
    ],

    computed: {
        /**
         * Lấy danh sách các node gốc.
         */
        rootList() {
            return this.data.filter(node => node.parentId == null);
        }
    },

    methods: {
        propagateClick(node) {
            this.$emit('click', node);
        }
    }
};
</script>


<style lang="scss">
$leftMargin: 20px;


%tree__widget {
    position: absolute;
    top: 6px;
    left: -5px - $leftMargin;
    z-index: 10;
    background-repeat: no-repeat;
    background-position: left center;
    // background-color: #CCC;
    // Cho to ra để trên mobile click được
    height: 18px;
    width: 18px;
    display: flex;
    justify-content: center;
    align-items: center;
}


.tree {
    margin-left: 0.25rem;

    ul {
        margin: 0;
        padding: 0;
        position: relative;
        list-style-type: none;
        display: none;

        &.expanded {
            display: block;
        }
    }

    li {
        // .text-nowrap
        // white-space: nowrap;
        margin: 0 0 0 $leftMargin;
        padding: 2px 0 2px 0;
        position: relative;
    }

    &__text {
        padding: 2px 5px;

        &--opened {
            font-weight: 700;
        }
    }
}


// Có đường kẻ nối các node
.tree--lined {
    $lineColor: #CCC;
    $bgColor: #FFF;

    background-color: $bgColor;

    ul {
        border-left: 1px dotted $lineColor;
    }

    li {
        // Extend a line to the list item
        &::before {
            position: absolute;
            top: 15px;
            left: -1 * $leftMargin + 1px;
            height: 1px;
            width: $leftMargin - 5px;
            border-top: 1px dotted $lineColor;
            content: ' ';
        }

        // Erase all lines for last item
        &:last-child::after {
            position: absolute;
            top: 11px;
            left: -1 * $leftMargin - 3px;
            bottom: 0;
            width: 7px;
            background-color: $bgColor;
            content: ' ';
        }
    }
}


// Có icon là ảnh
.tree--image {
    //&__leaf {
    .tree__leaf {
        @extend %tree__widget;

        // background-image: url(../images/leaf.gif);
        // background-image: url(../images/leaf.png);
    }

    //&__expander {
    .tree__expander {
        @extend %tree__widget;

        // background-image: url(../images/branch.gif);
        // background-image: url(../images/plus.png);
        cursor: pointer;

        &.expanded {
            // background-image: url(../images/branch--expanded.gif);
            // background-image: url(../images/minus.png);
        }
    }
}


// Có icon CSS
.tree--icon {
    .tree__leaf {
        @extend %tree__widget;

        &::before {
            width: 4px;
            height: 4px;
            content: '';
            border-radius: 50%;
            background-color: #42526e;
        }
    }

    .tree__expander {
        @extend %tree__widget;

        cursor: pointer;

        &::before {
            $size: 2px;

            width: 4px;
            height: 4px;
            content: '';

            border: solid #42526e;
            border-width: 0 $size $size 0;
            display: inline-block;
            padding: $size;
            transform: rotate(-45deg);
            transition: transform 0.2s;
        }


        &.expanded {
            &::before {
                transform: rotate(45deg);
            }
        }
    }
}


// Có thể chọn
.tree--selectable {
    .tree__text {
        cursor: pointer;

        &:hover {
            // background-color: #EEE;
        }

        &--selected {
            // background-color: yellow;
            font-weight: 500;
        }
    }

    .tree__text--disable {
        cursor: default;
    }
}


// Có thẻ click
.tree--clickable {
    .tree__text {
        cursor: pointer;

        &:hover {
            text-decoration: underline;
        }
    }

    .tree__text--disable {
        cursor: default;

        &:hover {
            text-decoration: none;
        }
    }
}
</style>
