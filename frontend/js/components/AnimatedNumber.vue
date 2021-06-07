<template>
    <span>
        {{formatNumber(displayNumber)}}
    </span>
</template>


<script>
export default {
    props: {
        // Con số cuối cùng, giá trị truyền vào
        number: {
            type: Number,
            default: 0
        },

        // Khoảng thời gian animation, tính theo milli giây
        duration: {
            type: Number,
            default: 2000
        },

        // Hàm ease
        // Tham số đầu vào là một số, khoảng thời gian đã trôi qua, trong khoảng 0 -> 1
        // Trả về khoảng cách tương ứng, trong khoảng từ 0 -> 1
        easeFunction: {
            type: Function,
            default: (x) => {
                // easeOutExpo
                return x === 1 ? 1 : 1 - Math.pow(2, -10 * x);
            }
        }
    },

    data() {
        return {
            // Con số hiển thị
            displayNumber: 0,
            // Thời điểm đánh dấu bắt đầu animation
            startTime: 0
        };
    },

    watch: {
        /**
         * Khi thay đổi giá trị thì thực hiện animation.
         */
        number() {
            this.animateNumber();
        }
    },

    methods: {
        /**
         * Thực hiện animation.
         */
        animateNumber() {
            this.startTime = performance.now();
            requestAnimationFrame(this.updateNumber);
        },

        /**
         * Hàm gọi mỗi frame khi animation.
         */
        updateNumber(currentTime) {
            const elapsedTime = currentTime - this.startTime;
            if (elapsedTime > this.duration) {
                // Đã kết thúc
                this.callback(this.number);
            } else {
                // Tính toán số hiện tại
                const timeRate = (1.0 * elapsedTime) / this.duration;
                const numberRate = this.easeFunction(timeRate);
                const currentNumber = Math.floor(numberRate * this.number);
                this.callback(currentNumber);

                // Thực hiện tiếp animation
                requestAnimationFrame(this.updateNumber);
            }
        },

        /**
         * Cập nhật giá trị hiện tại.
         */
        callback(currentNumber) {
            this.displayNumber = currentNumber;
        }
    }
};
</script>
