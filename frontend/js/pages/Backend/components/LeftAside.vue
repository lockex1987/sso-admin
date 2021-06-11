<template>
    <div class="sidebar border-right vh-100 overflow-y-auto overflow-x-hidden custom-scrollbar flex-shrink-0"
        :class="{
            'sidebar--desktop-opened': showLeftAside
        }">
        <div class="sidebar__inner py-2">
            <!-- Người dùng đang đăng nhập -->
            <div class="text-center pt-3"
                v-if="loginUser">
                <img :src="loginUser.avatar"
                    class="logo rounded-circle object-fit-cover"
                    v-if="loginUser.avatar"
                    onerror="this.src = '/images/user-avatar.png'" />
                <img src="/images/user-avatar.png"
                    class="logo rounded-circle object-fit-cover"
                    v-else />

                <div class="mt-3 mb-5">
                    {{loginUser.username}}
                </div>
            </div>

            <!-- Menu đầy đủ -->
            <div class="menu">
                <ul>
                    <li>
                        <router-link :to="{ name: 'dashboard' }"
                            class="sidebar-closer">
                            <i class="la la-dashboard font-size-1.25 mr-2"></i>
                            Dashboard
                        </router-link>
                    </li>

                    <li>
                        <router-link :to="{ name: 'notification' }"
                            class="sidebar-closer">
                            <i class="la la-bell font-size-1.25 mr-2"></i>
                            Thông báo
                            <span class="badge badge-warning"
                                v-if="unreadNotificationNumber">
                                {{unreadNotificationNumber}}
                            </span>
                        </router-link>
                    </li>

                    <li>
                        <router-link :to="{ name: 'chat' }"
                            class="sidebar-closer">
                            <i class="la la-sms font-size-1.25 mr-2"></i>
                            Chat (Web Socket)
                        </router-link>
                    </li>

                    <li>
                        <router-link :to="{ name: 'chatRtc' }"
                            class="sidebar-closer">
                            <i class="la la-sms font-size-1.25 mr-2"></i>
                            Chat (Web RTC)
                        </router-link>
                    </li>

                    <li>
                        <router-link :to="{ name: 'slide' }"
                            class="sidebar-closer">
                            <i class="la la-image font-size-1.25 mr-2"></i>
                            Slide
                        </router-link>
                    </li>

                    <li>
                        <router-link :to="{ name: 'content' }"
                            class="sidebar-closer">
                            <i class="la la-file-word font-size-1.25 mr-2"></i>
                            Nội dung
                        </router-link>
                    </li>

                    <li class="sub-menu">
                        <a href="#"
                            @click.prevent="">
                            <i class="la la-atom font-size-1.25 mr-2"></i>
                            Component demo
                            <i class="la la-angle-right arrow float-right"></i>
                        </a>

                        <ul>
                            <li>
                                <router-link :to="{ name: 'demoAutoComplete' }"
                                    class="sidebar-closer">
                                    <i class="la la-circle"></i>
                                    Auto Complete
                                </router-link>
                            </li>
                            <li>
                                <router-link :to="{ name: 'demoComboBox' }"
                                    class="sidebar-closer">
                                    <i class="la la-circle"></i>
                                    Combo Box
                                </router-link>
                            </li>
                            <li>
                                <router-link :to="{ name: 'demoTagsInput' }"
                                    class="sidebar-closer">
                                    <i class="la la-circle"></i>
                                    Tags Input
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <li class="sub-menu">
                        <a href="#"
                            @click.prevent="">
                            <i class="la la-users font-size-1.25 mr-2"></i>
                            Quản trị
                            <i class="la la-angle-right arrow float-right"></i>
                        </a>

                        <ul>
                            <li>
                                <router-link :to="{ name: 'app' }"
                                    class="sidebar-closer">
                                    <i class="la la-circle"></i>
                                    Ứng dụng
                                </router-link>
                            </li>

                            <li>
                                <router-link :to="{ name: 'permission' }"
                                    class="sidebar-closer">
                                    <i class="la la-circle"></i>
                                    Quyền
                                </router-link>
                            </li>

                            <li>
                                <router-link :to="{ name: 'role' }"
                                    class="sidebar-closer">
                                    <i class="la la-circle"></i>
                                    Vai trò
                                </router-link>
                            </li>

                            <li>
                                <router-link :to="{ name: 'user' }"
                                    class="sidebar-closer">
                                    <i class="la la-circle"></i>
                                    Người dùng
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <li class="sub-menu">
                        <a href="#"
                            @click.prevent="">
                            <i class="la la-layer-group font-size-1.25 mr-2"></i>
                            Danh mục
                            <i class="la la-angle-right arrow float-right"></i>
                        </a>

                        <ul>
                            <li>
                                <router-link :to="{ name: 'organization' }"
                                    class="sidebar-closer">
                                    <i class="la la-circle"></i>
                                    Tổ chức
                                </router-link>
                            </li>

                            <li>
                                <router-link :to="{ name: 'config' }"
                                    class="sidebar-closer">
                                    <i class="la la-circle"></i>
                                    Cấu hình hệ thống
                                </router-link>
                            </li>

                            <li>
                                <router-link :to="{ name: 'country' }"
                                    class="sidebar-closer">
                                    <i class="la la-circle"></i>
                                    Quốc gia
                                </router-link>
                            </li>

                            <li>
                                <router-link :to="{ name: 'province' }"
                                    class="sidebar-closer">
                                    <i class="la la-circle"></i>
                                    Tỉnh / thành phố
                                </router-link>
                            </li>

                            <li>
                                <router-link :to="{ name: 'district' }"
                                    class="sidebar-closer">
                                    <i class="la la-circle"></i>
                                    Huyện / quận
                                </router-link>
                            </li>

                            <li>
                                <router-link :to="{ name: 'commune' }"
                                    class="sidebar-closer">
                                    <i class="la la-circle"></i>
                                    Xã / phường
                                </router-link>
                            </li>

                            <li>
                                <router-link :to="{ name: 'category', params: { category: 'movie_format' } }"
                                    class="sidebar-closer">
                                    <i class="la la-circle"></i>
                                    Định dạng phim
                                </router-link>
                            </li>

                            <li>
                                <router-link :to="{ name: 'category', params: { category: 'movie_genre' } }"
                                    class="sidebar-closer">
                                    <i class="la la-circle"></i>
                                    Thể loại phim
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <router-link :to="{ name: 'systemLog' }"
                            class="sidebar-closer">
                            <i class="la la-history font-size-1.25 mr-2"></i>
                            Log hệ thống
                        </router-link>
                    </li>

                    <li>
                        <router-link :to="{ name: 'logFile' }"
                            class="sidebar-closer">
                            <i class="la la-file-code font-size-1.25 mr-2"></i>
                            File log
                        </router-link>
                    </li>

                    <li>
                        <a href="#"
                            @click.prevent="processLogout()"
                            class="sidebar-closer">
                            <i class="la la-sign-out font-size-1.25 mr-2"></i>
                            Đăng xuất
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>


<script>
import { getWsUrl } from '~/helpers/websocket.js';

export default {
    data() {
        return {
            // Số lượng thông báo chưa đọc
            unreadNotificationNumber: null,

            // Đối tượng WebSocket
            // lắng nghe khi có thông báo mới
            ws: null
        };
    },

    computed: {
        ...Vuex.mapState({
            showLeftAside: state => state.layout.showLeftAside
        })
    },

    mounted() {
        this.getUnreadNotificationNumber();

        // this.initWebSocket();
        this.handleClickEvents();
        this.openActiveMenu();
        this.handleToggleMenu();
    },

    methods: {
        /**
         * Hiển thị menu hiện tại (khi nhấn F5).
         */
        openActiveMenu() {
            const activeLink = $(this.$el).find('.router-link-active');
            const aTag = activeLink.closest('.sub-menu').find('a:first-of-type');
            aTag.next().slideDown();
            aTag.parent().addClass('open');
        },

        /**
         * Xử lý đóng mở nhóm menu con khi click vào các header.
         */
        handleToggleMenu() {
            $(this.$el).on('click', '.sub-menu > a', (evt) => {
                const aTag = $(evt.target.closest('a'));
                aTag.next().slideToggle();
                aTag.parent().toggleClass('open');
            });
        },

        /**
         * Lắng nghe các sự kiện chuột.
         */
        handleClickEvents() {
            document.addEventListener('click', (evt) => {
                let currentNode = evt.target;
                let clickOnBox = false;
                while (currentNode) {
                    if (currentNode.classList) {
                        // Click vào icon mở
                        if (currentNode.classList.contains('sidebar-opener')) {
                            document.body.classList.add('sidebar--mobile-opened');
                            return;
                        }

                        // Click vào icon đóng
                        if (currentNode.classList.contains('sidebar-closer')) {
                            document.body.classList.remove('sidebar--mobile-opened');
                            return;
                        }

                        if (currentNode.classList.contains('sidebar')) {
                            clickOnBox = true;
                            break;
                        }
                    }

                    currentNode = currentNode.parentNode;
                }

                // Nếu không click vào sidebar thì đóng
                if (!clickOnBox) {
                    document.body.classList.remove('sidebar--mobile-opened');
                }
            });
        },

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
            // console.log('Connecting');
            this.ws = new WebSocket(getWsUrl());


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
// Chiều rộng sidebar
$sidebarWidth: 250px;

.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    width: $sidebarWidth;
    overflow-x: hidden;
    z-index: 30;
    background-color: #fff;
    // Ẩn bên trái màn hình
    // Hiệu ứng chuyển động slide sang phải
    transition: all 0.4s ease-in-out;
    transform: translateX(-100%);

    @media (min-width: 768px) {
        position: relative;
        background: rgba(110, 174, 207, 0.15);
        position: relative;
        display: block;
        transform: translateX(0);
        width: 0px;

        // Trạng thái mở ở desktop
        &.sidebar--desktop-opened {
            width: $sidebarWidth;
        }
    }

    .sidebar__inner {
        // Phải cố định cả chiều rộng của của .sidebar__inner nếu không khi sidebar co lại thì chữ trong đó cũng bị co lại
        // Tránh trường hợp chiều rộng sidebar thay đổi làm nội dung bên trong bị co xuống dòng
        // Bằng kích thước của .sidebar--desktop-opened
        width: $sidebarWidth;

        .logo {
            width: 4rem;
            height: 4rem;
        }
    }
}

.menu {
    $orangeColor: #e06950;

    ul {
        padding: 0;

        li {
            list-style-type: none;

            a {
                text-decoration: none;
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

    .sub-menu {
        ul {
            display: none;

            li {
                // background: rgba(110, 174, 207, 0.25);
                border-bottom: none;

                a {
                    padding-left: 20px;
                }
            }
        }

        .arrow {
            margin-top: 0.25rem;
            margin-right: 0.75rem;
            transition: all 0.3s ease-in-out;
        }

        &.open {
            .arrow {
                transform: rotate(90deg);
            }
        }
    }
}
</style>

<style lang="scss">
$sidebarWidth: 250px;

// Trạng thái mở ở mobile
body.sidebar--mobile-opened {
    // Hiển thị overlay
    &::before {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        content: " ";
        background-color: rgba(0, 0, 0, 0.2);
        z-index: 20;
    }

    .sidebar {
        // width: $sidebarWidth;
        transform: translateX(0);
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); // .shadow-sm
    }
}
</style>
