const state = {
    user: null,
    permissions: []
};

const mutations = {
    setUser(state, userObj) {
        state.user = userObj;
    },

    setPermissions(state, permissionArray) {
        state.permissions = permissionArray;
    }
};

export default {
    state,
    mutations,
    namespaced: true,
    getters: {
        // Phải thêm getters ở đây thì ở checkAuthentication.js mới lấy được
        user: state => state.user
    }
};
