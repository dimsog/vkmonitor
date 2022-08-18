import { createApp } from 'vue';
import App from "./components/App.vue";
import axios from "axios";

window.axios = axios;
window.axios.defaults.headers.common = {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    'X-Requested-With': 'XMLHttpRequest'
};

createApp(App).mount('#app');
