import { createRouter, createWebHistory } from 'vue-router';
import Messages from './components/Messages.vue';
import MessagesComponent from './Components/MessagesComponent.vue';

import Login from './pages/Login.vue';
import Register from './pages/Register.vue';
import Dashboard from './pages/Dashboard.vue';
import { createApp } from 'vue';
import Chat from './pages/Chat.vue';

const routes = [
  { path: '/login', component: Login },
  { path: '/register', component: Register },
  { path: '/dashboard', component: Dashboard },
  { path: '/messages/:id', component: Messages, props: true } // ✅ Merged route
];

const router = createRouter({
  history: createWebHistory(),
  routes
});




const app = createApp({});
app.component('messages-component', MessagesComponent);
app.mount("#app");


export default router;
