<template>
    <div class="">
        <div class="chat-page d-flex flex-column mr-n5 h-100">
            <the-breadcrumb :paths="['Chat (Web Socket)']"/>

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
                    <chat-box
                            v-for="u in onlineList"
                            :u="u"
                            :key="u.id"
                            :ref="'box' + u.id"
                            :send-message="sendMessage"/>

                    <div class="position-absolute text-muted p-1 font-size-0.75 status">
                        {{status}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import ChatBox from './components/ChatBox';

const config = {
    wsUrl: 'ws://localhost:9000/'
};


export default {
    components: {
        ChatBox
    },

    data() {
        return {
            // Đối tượng WebSocket
            ws: null,
            // Danh sách người dùng online
            onlineList: [],
            // Trạng thái
            status: ''
        };
    },

    mounted() {
        // Khi logout cần đóng socket
        // Hiện tại, khi logout sẽ tự chuyển đến trang sso-passport,
        // nên cũng tự đóng socket rồi
        // this.closeWebSocket();

        this.initWebSocket();

        // $(document).on('keydown', this.disableF5);
    },

    methods: {
        /**
         * Gửi đến WebSocket server.
         */
        sendMessage(recipient, type, text) {
            if (this.ws.readyState == WebSocket.OPEN) {
                this.ws.send(JSON.stringify({
                    recipient,
                    type,
                    text
                }));
            } else {
                console.log('The socket is not open');
            }
        },

        /**
         * Khởi tạo websocket.
         */
        initWebSocket() {
            // Lấy token để xác thực websocket
            const token = localStorage.getItem('authToken');

            // `ws://${location.host}`
            // var HOST = location.origin.replace(/^http/, 'ws')
            this.ws = new WebSocket(config.wsUrl + '?token=' + token);

            this.ws.onopen = () => {
                // Lấy danh sách online
                this.sendMessage(null, 'online-list');
                this.status = 'Connected';
            };

            this.ws.onclose = () => {
                this.status = 'Closed';
            };

            this.ws.onerror = (error) => {
                console.log(error);
                noti.error('Sorry, but there is some problem with your connection or the server is down.');
            };

            this.ws.onmessage = (msg) => {
                const json = JSON.parse(msg.data);
                console.log(json);
                const type = json.type;
                const sender = json.sender;
                const text = json.text;

                if (type === 'join') {
                    const id = json.text;
                    const u = this.onlineList.find(e => e.id == id);
                    if (u) {
                        u.isOnline = true;
                    } else {
                        this.onlineList.push({
                            id: id,
                            name: json.text,
                            showBox: false,
                            getHistory: false,
                            isOnline: true
                        });
                    }

                    this.status = json.text + ' connected';
                } else if (type === 'online-list') {
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
                    const id = json.text;
                    const u = this.onlineList.find(e => e.id == id);
                    if (u) {
                        u.isOnline = false;
                    }

                    this.status = json.text + ' disconnected';
                } else if (type === 'message') {
                    this.status = sender + ': ' + text;
                    const u = this.onlineList.find(e => e.id == sender);
                    u.showBox = true;

                    // Phải thêm chỉ số 0 ở đây
                    if (u.getHistory) {
                        this.$refs['box' + sender][0].addEntry('msg-b', text);
                    } else {
                        this.checkHistory(u);
                    }
                } else if (type === 'typing') {
                    this.status = sender + ' is typing...';
                } else if (type === 'stop-typing') {
                    this.status = '';
                } else if (type === 'conversation-history') {
                    // Phải thêm chỉ số 0 ở đây
                    this.$refs['box' + json.with][0].bindHistory(json.history);
                } else {
                    this.status = 'Invalid JSON: ' + json;
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

        disableF5(evt) {
            if ((evt.which || evt.keyCode) == 116) {
                evt.preventDefault();
            }
        },

        openMessageBox(u) {
            u.showBox = true;
            this.checkHistory(u);
        },

        checkHistory(u) {
            if (!u.getHistory) {
                this.sendMessage(u.id, 'conversation-history');
                u.getHistory = true;
            }
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

.status {
    bottom: 0;
}

.chat-boxes {
    background: #1dd2af;
}
</style>
