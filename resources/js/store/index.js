import { createStore } from "vuex";

const store = createStore({
    state() {
        return { counter: 30 }
    },
    mutations: {
        increment(state) {
            state.count++
        }
    }
})

export default store;