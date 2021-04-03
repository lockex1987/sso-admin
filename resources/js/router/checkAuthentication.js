import store from '../store';


/**
 * Thực hiện đăng nhập tập chung ở passport.
 */
function redirectToSsoPassportLogin() {
    window.location = SSO_PASSPORT_URL + '/login?app=' + SSO_CONSUMER_DOMAIN;
}

/**
 * Kiểm tra token ở localStorage, user ở Vuex storage.
 */
export default async (to, from, next) => {
    let user = store.getters['auth/user'];
    let token = localStorage.getItem('authToken');
    const path = to.path;
    const ssoTicket = to.query.ssoTicket;

    // Nếu có token mà chưa có user thì lấy thông tin user
    if (token && !user) {
        const { data } = await axios.get('/me');
        if (data.code == 0) {
            user = data.user;
            store.commit('auth/setUser', user);
            store.commit('auth/setPermissions', data.permissions);
        } else {
            // token đã hết hạn
            localStorage.removeItem('authToken');
        }
    }

    if (ssoTicket && !user) {
        const params = {
            ticket: ssoTicket
        };
        const { data } = await axios.post('/login-callback', params);
        if (data.code == 0) {
            // Lưu token
            token = data.token;
            user = data.user;
            localStorage.setItem('authToken', token);
            store.commit('auth/setUser', user);
            store.commit('auth/setPermissions', data.permissions);
            next();
            return;
        } else {
            // Kiểm tra tiếp token
        }
    }

    // Kiểm tra các đường dẫn
    if (user) {
        if (path == '/' || path == '/login-callback') {
            next({
                name: 'dashboard'
            });
        } else {
            next();
        }
    } else {
        if (path == '/login-callback') {
            next();
        } else {
            redirectToSsoPassportLogin();
        }
    }
};
