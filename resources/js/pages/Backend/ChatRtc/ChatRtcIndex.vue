<template>
    <div class="">
        <div class="chat-page d-flex flex-column mr-n5 h-100">
            <the-breadcrumb :paths="['Chat (Web RTC)']"/>

            <div class="flex-1 d-flex">
                <div class="bg-light online-list">
                    <div class="user p-4 position-relative cursor-pointer"
                            v-for="u in onlineList"
                            :key="u.id"
                            @click="openMessageBox(u)">
                        <span class="rounded-circle d-inline-block mr-2 indicator"
                                :class="[u.isOnline ? 'bg-success' : 'bg-danger']"></span>
                        {{u.name}}
                    </div>
                </div>

                <div class="flex-1 position-relative pl-3 pt-3 chat-boxes">
                    <chat-rtc-box
                            v-for="u in onlineList"
                            :user="u"
                            :key="u.id"
                            :ref="'box' + u.id"
                            :send-message="sendMessage"/>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import ChatRtcBox from './components/ChatRtcBox';

const config = {
    wsUrl: 'ws://localhost:9000/'
};


export default {
    components: {
        ChatRtcBox
    },

    data() {
        return {
            // Đối tượng WebSocket
            ws: null,

            // Danh sách người dùng online
            onlineList: []
        };
    },

    mounted() {
        // Khi logout cần đóng socket
        // Hiện tại, khi logout sẽ tự chuyển đến trang sso-passport,
        // nên cũng tự đóng socket rồi
        // this.closeWebSocket();

        this.initWebSocket();
    },

    methods: {
        /**
         * Gửi đến WebSocket server.
         */
        sendMessage(recipient, type, obj) {
            if (this.ws.readyState == WebSocket.OPEN) {
                this.ws.send(JSON.stringify({
                    recipient,
                    type,
                    ...obj
                }));
            } else {
                console.log('The socket is not open');
            }
        },

        initWebSocket() {
            const token = localStorage.getItem('authToken');
            // `ws://${location.host}`
            // var HOST = location.origin.replace(/^http/, 'ws')
            this.ws = new WebSocket(config.wsUrl + '?token=' + token);

            this.ws.onopen = () => {
                // Lấy danh sách online
                this.sendMessage(null, 'online-list');
                console.log('Connected');
            };

            this.ws.onclose = () => {
                console.log('Closed');
            };

            this.ws.onerror = (error) => {
                console.log(error);
                noti.error('Sorry, but there is some problem with your connection or the server is down.');
            };

            this.ws.onmessage = (msg) => {
                const json = JSON.parse(msg.data);
                console.log(json);
                const type = json.type;

                if (type === 'join') {
                    // Một người dùng vừa mới vào chat
                    const id = json.text;
                    const u = this.onlineList.find(e => e.id == id);
                    if (u) {
                        u.isOnline = true;
                        this.$refs['box' + u.id][0].checkRtcConnection();
                    } else {
                        this.onlineList.push({
                            id: id,
                            name: json.text,
                            showBox: false,
                            getHistory: false,
                            isOnline: true
                        });
                    }

                    console.log(json.text + ' connected');
                } else if (type === 'online-list') {
                    // Danh sách người dùng online hiện tại
                    json.onlineList.forEach((e) => {
                        this.onlineList.push({
                            id: e,
                            name: e,
                            showBox: false,
                            getHistory: false,
                            isOnline: true
                        });
                    });
                } else if (type === 'quit') {
                    // Một người dùng nào đó thoát chat
                    const id = json.text;
                    const u = this.onlineList.find(e => e.id == id);
                    if (u) {
                        u.isOnline = false;
                    }

                    console.log(json.text + ' disconnected');

                    this.$refs['box' + json.text][0].disconnectPeer();
                } else if (type === 'rtc-offer') {
                    this.$refs['box' + json.sender][0].processOffer(json.offer);
                } else if (type === 'rtc-answer') {
                    this.$refs['box' + json.sender][0].processAnswer(json.answer);
                } else if (type === 'rtc-ice-candidate') {
                    this.$refs['box' + json.sender][0].addIceCandidate(json.candidate);
                } else {
                    console.log('Invalid JSON: ' + json);
                }
            };
        },

        /**
         * Đóng websocket.
         */
        closeWebSocket() {
            this.ws.close();
            this.ws = null;
        },

        openMessageBox(u) {
            u.showBox = true;

            // Phải thêm chỉ số 0 ở đây
            this.$refs['box' + u.id][0].checkPeer();
        }
    },

    /**
     * Khi người dùng chuyển trang thì đóng socket.
     */
    beforeRouteLeave(to, from, next) {
        console.log('Close websocket');
        this.closeWebSocket();
        next();
    }
};
</script>


<style lang="scss" scoped>
.online-list {
    width: 280px;

    .user {
        &:hover {
            background: #EEE;
        }

        .indicator {
            width: 10px;
            height: 10px;
        }
    }
}

.chat-boxes {
    background: #1dd2af;
}
</style>
