<template>
    <div class="lef-aside">
        <div class="wrapper py-2 h-100">
            <div class="text-center pt-3" v-if="user">
                <img :src="'http://sso-passport.cttd.tk' + '/storage/avatars/' + user.avatar"
                        class="logo rounded-circle object-fit-cover"
                        v-if="user.avatar"
                        onerror="this.src = '/images/user-avatar.png'"/>
                <img src="/images/user-avatar.png"
                        class="logo rounded-circle object-fit-cover"
                        v-else/>

                <div class="mt-3 mb-5">{{user.username}}</div>
            </div>

            <!-- Menu đầy đủ -->
            <div class="adjust-height custom-scrollbar overflow-auto">
                <div class="menu">
                    <ul class="menu">
                        <li>
                            <router-link :to="{ name: 'dashboard' }" class="text-decoration-none">
                                <i class="la la-dashboard font-size-1.25 mr-2"></i>
                                Dashboard
                            </router-link>
                        </li>

                        <li>
                            <router-link :to="{ name: 'notification' }" class="text-decoration-none">
                                <i class="la la-bell font-size-1.25 mr-2"></i>
                                Thông báo
                                <span class="badge badge-warning"
                                        v-if="unreadNotificationNumber">
                                    {{unreadNotificationNumber}}
                                </span>
                            </router-link>
                        </li>

                        <li>
                            <router-link :to="{ name: 'app' }" class="text-decoration-none">
                                <i class="lab la-app-store font-size-1.25 mr-2"></i>
                                Ứng dụng
                            </router-link>
                        </li>

                        <li>
                            <router-link :to="{ name: 'permission' }" class="text-decoration-none">
                                <i class="la la-shield-alt font-size-1.25 mr-2"></i>
                                Quyền
                            </router-link>
                        </li>

                        <li>
                            <router-link :to="{ name: 'role' }" class="text-decoration-none">
                                <i class="la la-users font-size-1.25 mr-2"></i>
                                Vai trò
                            </router-link>
                        </li>

                        <li>
                            <router-link :to="{ name: 'user' }" class="text-decoration-none">
                                <i class="la la-user font-size-1.25 mr-2"></i>
                                Người dùng
                            </router-link>
                        </li>

                        <li>
                            <router-link :to="{ name: 'chat' }" class="text-decoration-none">
                                <i class="la la-sms font-size-1.25 mr-2"></i>
                                Chat (Web Socket)
                            </router-link>
                        </li>

                        <li>
                            <router-link :to="{ name: 'chatRtc' }" class="text-decoration-none">
                                <i class="la la-sms font-size-1.25 mr-2"></i>
                                Chat (Web RTC)
                            </router-link>
                        </li>

                        <li>
                            <router-link :to="{ name: 'organization' }" class="text-decoration-none">
                                <i class="la la-project-diagram font-size-1.25 mr-2"></i>
                                Tổ chức
                            </router-link>
                        </li>

                        <li>
                            <router-link :to="{ name: 'config' }"
                                    class="text-decoration-none">
                                <i class="la la-sliders-h font-size-1.25 mr-2"></i>
                                Cấu hình hệ thống
                            </router-link>
                        </li>

                        <li>
                            <router-link :to="{ name: 'country' }"
                                    class="text-decoration-none">
                                <i class="la la-layer-group font-size-1.25 mr-2"></i>
                                Quốc gia
                            </router-link>
                        </li>

                        <li>
                            <router-link :to="{ name: 'province' }"
                                    class="text-decoration-none">
                                <i class="la la-layer-group font-size-1.25 mr-2"></i>
                                Tỉnh / thành phố
                            </router-link>
                        </li>

                        <li>
                            <router-link :to="{ name: 'district' }"
                                    class="text-decoration-none">
                                <i class="la la-layer-group font-size-1.25 mr-2"></i>
                                Huyện / quận
                            </router-link>
                        </li>

                        <li>
                            <router-link :to="{ name: 'commune' }"
                                    class="text-decoration-none">
                                <i class="la la-layer-group font-size-1.25 mr-2"></i>
                                Xã / phường
                            </router-link>
                        </li>

                        <li>
                            <router-link :to="{ name: 'slide' }"
                                    class="text-decoration-none">
                                <i class="la la-image font-size-1.25 mr-2"></i>
                                Slide
                            </router-link>
                        </li>

                        <li>
                            <router-link :to="{ name: 'category', params: { category: 'movie_format' } }"
                                    class="text-decoration-none">
                                <i class="la la-layer-group font-size-1.25 mr-2"></i>
                                Định dạng phim
                            </router-link>
                        </li>

                        <li>
                            <router-link :to="{ name: 'category', params: { category: 'movie_genre' } }"
                                    class="text-decoration-none">
                                <i class="la la-layer-group font-size-1.25 mr-2"></i>
                                Thể loại phim
                            </router-link>
                        </li>

                        <li>
                            <router-link :to="{ name: 'content' }"
                                    class="text-decoration-none">
                                <i class="la la-file-word font-size-1.25 mr-2"></i>
                                Nội dung
                            </router-link>
                        </li>

                        <li class="sub-menu d-none">
                            <a href="#">
                                <i class="la la-atom font-size-1.25 mr-2"></i>
                                Component demo
                                <i class="la la-angle-right arrow float-right"></i>
                            </a>

                            <ul>
                                <li>
                                    <router-link :to="{ name: 'demoAutoComplete' }">
                                        <i class="la la-circle"></i>
                                        Auto Complete
                                    </router-link>
                                </li>
                                <li>
                                    <router-link :to="{ name: 'demoComboBox' }">
                                        <i class="la la-circle"></i>
                                        Combo Box
                                    </router-link>
                                </li>
                                <li>
                                    <router-link :to="{ name: 'demoTagsInput' }">
                                        <i class="la la-circle"></i>
                                        Tags Input
                                    </router-link>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <router-link :to="{ name: 'systemLog' }"
                                    class="text-decoration-none">
                                <i class="la la-history font-size-1.25 mr-2"></i>
                                Log hệ thống
                            </router-link>
                        </li>

                        <li>
                            <router-link :to="{ name: 'logFile' }"
                                    class="text-decoration-none">
                                <i class="la la-file-code font-size-1.25 mr-2"></i>
                                File log
                            </router-link>
                        </li>

                        <li>
                            <a href="javascript:;" @click="processLogout()" class="text-decoration-none">
                                <i class="la la-sign-out font-size-1.25 mr-2"></i>
                                Đăng xuất
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
const config = {
    wsUrl: 'ws://localhost:9000/'
};

export default {
    computed: {
        ...Vuex.mapState({
            user: state => state.auth.user
        })
    },

    data() {
        return {
            // Số lượng thông báo chưa đọc
            unreadNotificationNumber: null,

            // Đối tượng WebSocket
            // lắng nghe khi có thông báo mới
            ws: null
        };
    },

    mounted() {
        this.getUnreadNotificationNumber();

        // this.initWebSocket();
    },

    methods: {
        /**
         * Thực hiện đăng xuất.
         */
        async processLogout() {
            // Gọi API đăng xuất
            await axios.post('/logout');

            // Xóa session và chuyển đến trang login
            localStorage.removeItem('authToken');
            this.$store.commit('auth/setUser', null);

            window.location = SSO_PASSPORT_URL + '/login?logout=true&app=' + SSO_CONSUMER_DOMAIN;
        },

        /**
         * Lấy số lượng tin nhắn chưa đọc.
         */
        async getUnreadNotificationNumber() {
            const { data } = await axios.get('/notification/unread-number');
            this.unreadNotificationNumber = data.number;
        },

        /**
         * Khởi tạo websocket.
         */
        initWebSocket() {
            // Lấy token để xác thực websocket
            const token = localStorage.getItem('authToken');

            // console.log('Connecting');
            this.ws = new WebSocket(config.wsUrl + '?token=' + token);


            this.ws.onopen = () => {
                console.log('Connected');
            };

            this.ws.onmessage = (msg) => {
                const json = JSON.parse(msg.data);
                console.log(json);
                const type = json.type;

                if (type === 'notification') {
                    const message = json.message;
                    noti.info('<div class="text-muted">Bạn có thông báo mới</div><div>' + message + '</div>');
                    this.getUnreadNotificationNumber();
                }
            };
        }
    }
};
</script>


<style scoped lang="scss">
.lef-aside {
    flex-shrink: 0;
    background: rgba(110, 174, 207, 0.15);
    border-top-right-radius: 0.25rem;
    width: 230px;

    .logo {
        width: 4rem;
        height: 4rem;
    }

    .menu {
        $orangeColor: #E06950;

        ul {
            padding: 0;

            li {
                list-style-type: none;

                a {
                    display: block;
                    padding: 10px 0 10px 5px;
                    border-left: solid transparent 3px;

                    &:hover {
                        color: $orangeColor;
                    }

                    // Menu đang active
                    &.router-link-active {
                        border-left-color: $orangeColor;
                        color: $orangeColor;
                    }

                    i {
                        min-width: 20px;
                    }
                }
            }
        }
    }
}

.adjust-height {
    height: calc(100vh - 190px);
}
</style>
