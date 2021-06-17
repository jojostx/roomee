require('./bootstrap');
require('alpinejs');
import { createApp } from 'vue';
import App from './App.vue'
import router from './router';
import store from './store';

const app = createApp(App);
app.use(store)
    // app.use(router)
app.mount('#app');


// async function test() {
//     const result = await axios.get('http://127.0.0.1:8000/api/v1/users');
//     const data = await result.data;
//     console.log(data);
// }

// test();