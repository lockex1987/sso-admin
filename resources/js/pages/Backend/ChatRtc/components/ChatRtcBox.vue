<template>
    <div class="message-box float-left mb-3 mr-3 bg-white rounded-top"
            v-show="user.showBox">
        <div class="header rounded-top pl-3 pr-3 pt-3 pb-4 text-white">
            {{user.name}}x

            <div class="float-right cursor-pointer"
                    @click.stop="user.showBox = false">
                &times;
            </div>
        </div>

        <div class="d-flex px-1 pt-1 pb-3 justify-content-between d-none">
            <button @click="startCapture()" class="btn btn-success">
                Start capture
            </button>

            <button @click="stopCapture()" class="btn btn-danger">
                Stop capture
            </button>
        </div>

        <div class="mb-3 video-container p-2 d-none">
            <video ref="localVideo" autoplay class="w-100 h-auto"/>
        </div>

        <div class="mb-3 video-container p-2 d-none">
            <video ref="remoteVideo" autoplay class="w-100 h-auto"/>
        </div>

        <div class="message-container font-size-0.875 custom-scrollbar" ref="container"></div>

        <textarea class="message-input w-100"
                placeholder="Nội dung tin nhắn"
                @keydown.enter.prevent="sendMessageToOther()"
                v-model.trim="message"
                :readonly="!user.isOnline || !isConnected"
                ref="messageInputBox"></textarea>
    </div>
</template>


<script>
export default {
    props: [
        // Người dùng
        'user',
        // Gửi message cho Web Socket
        'sendMessage'
    ],

    data() {
        return {
            // Nội dung tin nhắn
            message: '',

            // Trạng thái đang kết nối hay không
            isConnected: false,

            // Chỉ có một Connection
            connection: null,

            // Channel cho gửi dữ liệu
            sendChannel: null,

            // Channel cho nhận dữ liệu
            receiveChannel: null
        };
    },

    mounted() {
        this.setupConnection();
    },

    beforeDestroy() {
        console.log('Giải phóng tài nguyên');
        this.disconnectPeer();
    },

    methods: {
        /**
         * Kiểm tra kết nối (khi click vào danh sách người dùng bên trái).
         */
        checkPeer() {
            if (!this.isConnected) {
                this.connectPeer();
            }
        },

        /**
         * Khi chưa có connection thì khởi tạo.
         */
        checkRtcConnection() {
            if (!this.connection) {
                this.setupConnection();
            }
        },

        /**
         * Khởi tạo kết nối.
         */
        async setupConnection() {
            // Tạo đối tượng kết nối
            this.connection = new RTCPeerConnection();

            // Tạo channel gửi
            this.sendChannel = this.connection.createDataChannel('sendChannel');
            this.setupSendChannel();

            // Tạo channel nhận
            // Hàm này được gọi khi mà các kết nối được mở, sẵn sàng
            this.connection.ondatachannel = (evt) => {
                this.receiveChannel = evt.channel;

                this.setupReceiveChannel();
            };

            // Lắng nghe khi sinh xong ICE candidate thì gửi đi các máy khác
            this.connection.onicecandidate = (evt) => {
                if (evt.candidate) {
                    // Gửi candiate cho remote
                    this.sendMessage(this.user.id, 'rtc-ice-candidate', { candidate: evt.candidate });
                }
            };

            // Thiết lập stream:
            // - Video
            // - Audio
            // - Screen

            /*
            // Phải gọi luôn ở đây?
            await this.startCapture();

            // Khi máy remote thêm stream thì hiển thị nó
            this.connection.onaddstream = (evt) => {
                // this.$refs.remoteVideo.src = window.URL.createObjectURL(evt.stream);
                this.$refs.remoteVideo.srcObject = evt.stream;
            };
            */
        },

        async startCapture() {
            const displayMediaOptions = {
                video: {
                    cursor: 'always'
                },
                audio: false
            };

            // Phải dùng trên HTTPS
            // Hoặc là mở với tùy chọn:
            // chromium-browser --unsafely-treat-insecure-origin-as-secure="http://sso-admin.cttd.tk"
            // google-chrome    --unsafely-treat-insecure-origin-as-secure="http://sso-admin.cttd.tk"
            // Sử dụng navigator.mediaDevices.getUserMedia() cho video, audio
            const stream = await navigator.mediaDevices.getDisplayMedia(displayMediaOptions);
            this.$refs.localVideo.srcObject = stream;

            // Thêm vào WebRTC
            this.connection.addStream(stream);
        },

        stopCapture(evt) {
            this.$refs.localVideo.srcObject
                .getTracks()
                .forEach(track => {
                    track.stop();
                });
            this.$refs.localVideo.srcObject = null;
        },

        /**
         * Tạo offer đến máy khác.
         */
        async connectPeer() {
            const offer = await this.connection.createOffer();
            await this.connection.setLocalDescription(offer);
            this.sendMessage(this.user.id, 'rtc-offer', { offer });
        },

        /**
         * Xử lý offer từ máy khác gửi đến.
         */
        async processOffer(offer) {
            await this.connection.setRemoteDescription(new RTCSessionDescription(offer));

            // Gửi lại answer
            const answer = await this.connection.createAnswer();
            await this.connection.setLocalDescription(answer);
            this.sendMessage(this.user.id, 'rtc-answer', { answer });
        },

        /**
         * Xử lý answer từ máy khác gửi đến.
         */
        async processAnswer(answer) {
            await this.connection.setRemoteDescription(new RTCSessionDescription(answer));
        },

        /**
         * Thêm ICE candiate của máy khác.
         */
        async addIceCandidate(candidate) {
            await this.connection.addIceCandidate(new RTCIceCandidate(candidate));
        },

        /**
         * Click nút đóng kết nối.
         */
        disconnectPeer() {
            // Đóng các data channel và các kết nối
            if (this.sendChannel) {
                this.sendChannel.close();
            }
            if (this.receiveChannel) {
                this.receiveChannel.close();
            }
            if (this.connection) {
                this.connection.close();
            }

            this.sendChannel = null;
            this.receiveChannel = null;
            this.connection = null;

            this.isConnected = false;

            this.message = '';
        },

        /**
         * Gửi tin nhắn đến người khác.
         */
        sendMessageToOther() {
            if (this.message) {
                // Thêm và gửi
                this.addEntry('message-a', this.message);

                this.sendChannel.send(this.message);
            }

            // Sẵn sàng gửi tiếp
            this.message = '';
            this.$refs.messageInputBox.focus();
        },

        /**
         * Hiển thị thêm tin nhắn.
         */
        addEntry(msgClass, message) {
            const entry = document.createElement('div');
            this.$refs.container.appendChild(entry);

            entry.style.display = 'none';
            entry.classList.add(msgClass);
            entry.textContent = message;
            $(entry).fadeIn();

            this.$refs.container.scrollTop = this.$refs.container.scrollHeight;
        },

        setupReceiveChannel() {
            this.receiveChannel.onmessage = (evt) => {
                const message = evt.data;
                this.addEntry('message-b', message);
                this.user.showBox = true;
            };

            this.receiveChannel.onopen = this.handleReceiveChannelStatusChange;
            this.receiveChannel.onclose = this.handleReceiveChannelStatusChange;
        },

        setupSendChannel() {
            this.sendChannel.onopen = this.handleSendChannelStatusChange;
            this.sendChannel.onclose = this.handleSendChannelStatusChange;
        },

        /**
         * Lắng nghe sự kiện thay đổi trạng thái của sendChannel.
         * @param {Event} evt
         */
        handleSendChannelStatusChange(evt) {
            if (this.sendChannel && this.sendChannel.readyState === 'open') {
                this.isConnected = true;
                this.$refs.messageInputBox.focus();
            } else {
                this.isConnected = false;
            }
        },

        /**
         * Lắng nghe sự kiện đóng mở channel nhận.
         * @param {Event}} evt
         */
        handleReceiveChannelStatusChange(evt) {
            // console.log('receiveChannel', this.receiveChannel);
            // console.log('Compare', this.sendChannel === this.receiveChannel);
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


.message-box {
    width: 250px;

    .header {
        background: #3498db;
    }

    &:focus-within {
        .header {
            background: #e78f08;
        }
    }

    .message-container {
        height: 250px;
        overflow-x: hidden;
        overflow-y: auto;
    }

    .message-a {
        @include msgEntry(#99FFCC);

        &::before {
            @include speechBubble;

            left: -22px;
            border-color: transparent #99FFCC transparent transparent;
        }
    }

    .message-b {
        @include msgEntry(#FFFF99);

        &::before {
            @include speechBubble;

            right: -22px;
            border-color: transparent transparent transparent #FFFF99;
        }
    }

    .message-input {
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
