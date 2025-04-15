// src/router.ts
import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router';
import Login from '../views/Login.vue';

import Painel from '../views/Painel.vue';
const routes: Array<RouteRecordRaw> = [
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/painel',
    name: 'Painel',
    component: Painel
  },
  {
    path: '/',
    redirect: '/login' 
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
