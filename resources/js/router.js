import { createRouter, createWebHistory } from 'vue-router';
import Login from './pages/Login.vue';
import Register from './pages/Register.vue';
import Dashboard from './pages/Dashboard.vue';
import Messages from './components/Messages.vue';
import { createApp } from 'vue';
import Chat from './pages/Chat.vue';
import axios from 'axios';

// Define routes
const routes = [
  { path: '/login', component: Login, meta: { guest: true } },
  { path: '/register', component: Register, meta: { guest: true } },
  { path: '/dashboard', component: Dashboard, meta: { requiresAuth: true } },
  { path: '/messages/:id', component: Messages, props: true, meta: { requiresAuth: true } }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

// ✅ Middleware to check authentication before accessing protected pages
router.beforeEach(async (to, from, next) => {
  const token = localStorage.getItem('token');

  if (to.meta.requiresAuth) {
    if (!token) {
      return next('/login'); // Redirect if not authenticated
    }

    // ✅ Verify token with backend
    try {
      const response = await axios.get('/api/user', {
        headers: { Authorization: `Bearer ${token}` }
      });
      localStorage.setItem('user', JSON.stringify(response.data)); // Store user data
      next();
    } catch (error) {
      console.error('Auth check failed:', error);
      localStorage.removeItem('token'); // Remove invalid token
      return next('/login');
    }
  } else if (to.meta.guest && token) {
    return next('/dashboard'); // Prevent logged-in users from accessing login/register
  } else {
    next();
  }
});

export default router;
