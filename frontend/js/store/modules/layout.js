const state = {
    showLeftAside: localStorage.getItem('showLeftAside')
};

const mutations = {
    /**
     * Ẩn hiện menu desktop.
     */
    toggleLeftAside(state) {
        if (state.showLeftAside) {
            localStorage.removeItem('showLeftAside');
            state.showLeftAside = false;
        } else {
            localStorage.setItem('showLeftAside', true);
            state.showLeftAside = true;
        }
    }
};

export default {
    state,
    mutations,
    namespaced: true
};
