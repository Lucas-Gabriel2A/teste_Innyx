import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import Antd from 'ant-design-vue';
import router from './Router/Router';



const app = createApp(App);
app.use(router);
app.use(Antd); 
app.mount('#app');
