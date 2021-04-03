<template>
    <div class="msg-box float-left mb-3 mr-3 bg-white rounded-top"
            v-show="u.showBox">
        <div class="header rounded-top pl-3 pr-3 pt-3 pb-4 text-white">
            {{u.name}}

            <div class="float-right cursor-pointer"
                    @click.stop="u.showBox = false">
                &times;
            </div>
        </div>

        <div class="msg-container font-size-0.875 custom-scrollbar" ref="container"></div>

        <textarea class="msg-input w-100"
                placeholder="Enter your message"
                @keydown.enter.prevent="sendMessageToOther()"
                @input="checkTyping()"
                v-model.trim="msg"
                :readonly="!u.isOnline"></textarea>
    </div>
</template>


<script>
export default {
    props: [
        'u',
        'sendMessage'
    ],

    data() {
        return {
            typingTimer: null,
            isTyping: false,
            msg: ''
        };
    },

    methods: {
        sendMessageToOther() {
            if (this.msg) {
                // Thêm và gửi
                this.addEntry('msg-a', this.msg);
                this.sendMessage(this.u.id, 'message', this.msg);
                clearTimeout(this.typingTimer);
                this.isTyping = false;
            }
            this.msg = '';
        },

        checkTyping() {
            if (this.isTyping) {
                clearTimeout(this.typingTimer);
            } else {
                this.isTyping = true;
                this.sendMessage(this.u.id, 'typing', '');
            }

            this.typingTimer = setTimeout(() => {
                this.sendMessage(this.u.id, 'stop-typing', '');

                this.isTyping = false;
            }, 600);
        },

        addEntry(msgClass, msg, toHead = false) {
            const entry = document.createElement('div');
            if (toHead) {
                this.$refs.container.insertBefore(entry, this.$refs.container.firstChild);
            } else {
                this.$refs.container.appendChild(entry);
            }

            entry.style.display = 'none';
            entry.classList.add(msgClass);
            entry.textContent = msg;
            $(entry).fadeIn();

            this.$refs.container.scrollTop = this.$refs.container.scrollHeight;
        },

        bindHistory(history) {
            history.forEach(s => {
                const e = JSON.parse(s);
                const msgClass = (e.from == this.u.id) ? 'msg-b' : 'msg-a';
                const msg = e.text;
                this.addEntry(msgClass, msg, true);
            });
        }
    }
};
</script>


<style lang="scss">
@mixin msgEntry($bgColor) {
    margin: 5px 10px;
    padding: 15px;
    position: relative;
    border-radius: 5px;
    background: $bgColor;
}

@mixin speechBubble {
    content: "";
    width: 0;
    height: 0;
    border: 15px solid;
    position: absolute;
    top: 7px;
}


.msg-box {
    width: 250px;

    .header {
        background: #3498db;
    }

    &:focus-within {
        .header {
            background: #e78f08;
        }
    }

    .msg-container {
        height: 250px;
        overflow-x: hidden;
        overflow-y: auto;
    }

    .msg-a {
        @include msgEntry(#99FFCC);

        &::before {
            @include speechBubble;

            left: -22px;
            border-color: transparent #99FFCC transparent transparent;
        }
    }

    .msg-b {
        @include msgEntry(#FFFF99);

        &::before {
            @include speechBubble;

            right: -22px;
            border-color: transparent transparent transparent #FFFF99;
        }
    }

    .msg-input {
        outline: none;
        resize: none;
        padding: .375rem .75rem;
        height: 70px;
        border: transparent;
        border-top: 1px solid #bdc3c7;
        background: #fdf9e1;

        &:focus {
            border-top-color: #69F;
            background: #FFFFFF;
        }
    }
}
</style>
