<template>
    <div class="custom-slider py-2">
        <div class="position-relative">
            <!-- Ẩn background, không đè lên cái thứ nhất -->
            <input type="range"
                    class="w-100 position-absolute rounded"
                    :min="min"
                    :max="max"
                    :step="step"
                    :value="value"
                    @input="checkUpperRange()"
                    @change="$emit('change')"
                    ref="upperRange"/>

            <div class="middle position-absolute"
                    ref="middle"
                    style="left: 0"></div>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        min: {
            type: Number,
            default: 0
        },
        max: {
            type: Number,
            default: 100
        },
        value: {
            type: Number
        },
        step: {
            type: Number,
            default: 1
        }
    },

    mounted() {
        this.updatePosition();
    },

    methods: {
        checkUpperRange() {
            this.updateEvenAndUpdatePosition();
        },

        emitInputEvent() {
            this.$emit('input', parseFloat(this.$refs.upperRange.value));
        },

        updatePosition() {
            const upper = this.$refs.upperRange.value;
            const diff = this.max - this.min;
            const width = upper / diff;
            this.$refs.middle.style.width = `calc((100% - 1rem) * ${width})`; //  - 1rem
        },

        updateEvenAndUpdatePosition() {
            this.emitInputEvent();
            this.updatePosition();
        }
    }
};
</script>


<style lang="scss">
.custom-slider {
    height: 1.5rem;

    // Thêm padding để không che hai shadown hình tròn
    padding-left: 1px;
    padding-right: 1px;

    $height: 6px;

    input[type=range] {
        -webkit-appearance: none;
        height: $height;
        background: #e9ecef;
        // Không cho click
        pointer-events: none;

        &::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 1rem;
            width: 1rem;
            background-color: #fff;
            border-radius: 50%;
            box-shadow: 0.5px 0.5px 2px 1px rgba(0, 0, 0, 0.32);
            cursor: pointer;
            // Cho phép kéo
            pointer-events: all;
            z-index: 3;
        }
    }

    .middle {
        top: 0px;
        height: $height;
        background-color: #007bff;
        z-index: 1;
        // .rounded
        border-radius: .25rem 0 0 .25rem;
    }
}
</style>
