<template>
    <div class="auto-complete-page">
        <the-breadcrumb :paths="['Component demo', 'Auto complete']" />

        <div class="mb-3">
            Selected:
            <span class="text-info">
                {{selectedMovie.name}}
            </span>
        </div>

        <div class="position-relative">
            <input v-model.trim="searchText"
                ref="inputTag"
                placeholder="Nhập tên phim để tìm kiếm"
                class="form-control"
                type="text"
                autocomplete="off"
                @input="filterAutocompleteWhenInput()"
                @keydown="handleAutocompleteControls($event)">

            <auto-complete :options="movieList"
                :selected-item="selectedMovie"
                ref="autoComplete"
                @change="selectedMovie = $event">
                <template v-slot:item-template="slotProps">
                    <img :src="slotProps.item.avatar || '/images/no-image.svg'"
                        class="mr-2 rounded avatar" />
                    {{slotProps.item.name}}
                </template>
            </auto-complete>
        </div>
    </div>
</template>


<script>
export default {
    data() {
        return {
            movieList: [],
            searchText: '',
            selectedMovie: {}
        };
    },

    methods: {
        /**
         * Hàm thực hiện khi người dùng nhập giá trị.
         */
        filterAutocompleteWhenInput() {
            if (this.searchText) {
                this.searchMovie();
            } else {
                this.$refs.autoComplete.hide();
            }
        },

        /**
         * Thay đổi giá trị chọn khi người dùng nhấn phím UP hoặc DOWN.
         * Chọn khi người dùng nhấn ENTER.
         * Ẩn khi người dùng nhấn TAB.
         */
        handleAutocompleteControls(evt) {
            const keyCode = evt.keyCode;

            if (keyCode == 40) {
                // Mũi tên xuống
                this.$refs.autoComplete.moveDown();
            } else if (keyCode == 38) {
                // Mũi tên lên
                this.$refs.autoComplete.moveUp();
            } else if (keyCode == 13) {
                // Nhấn ENTER
                // Không submit form
                evt.preventDefault();

                // Đang có một giá trị được active
                if (this.$refs.autoComplete.selectCurrent()) {
                    this.$refs.inputTag.blur();
                }
            } else if (keyCode == 9) {
                // Nhấn TAB thì ẩn
                this.$refs.autoComplete.hide();
            }
        },

        async searchMovie() {
            const apiKey = '1e0dcaa7e93980fb84e1d2430d01b887';
            const url = 'https://api.themoviedb.org/3/search/multi' +
                    '?api_key=' + apiKey +
                    '&query=' + encodeURIComponent(this.searchText);
            const data = await fetch(url)
                .then(resp => resp.json());

            this.movieList = data.results.map(e => ({
                id: e.id,
                name: e.name || e.title,
                avatar: (e.poster_path ? `http://image.tmdb.org/t/p/w500${e.poster_path}` : null)
            }));
            this.$refs.autoComplete.show();
        }
    }
};
</script>


<style lang="scss">
.auto-complete-page {
    background-image: url(https://static.pexels.com/photos/1043/red-building-industry-bricks-large.jpg);
    background-size: cover;

    .autocomplete__dropdown {
        background-color: rgba(0, 0, 0, 0.7);
    }

    .autocomplete__item {
        font-size: 14px;
        color: #fff;
    }

    .autocomplete__item .avatar {
        width: 64px;
        height: 64px;
        object-fit: cover;
    }
}
</style>
