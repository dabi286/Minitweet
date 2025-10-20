import { createRouter, createWebHistory } from "vue-router";
import AuthPage from "../components/AuthPage.vue";
import FeedPage from "../components/FeedPage.vue";

const routes = [
  { path: "/auth", component: AuthPage },
  { path: "/feed", component: FeedPage, meta: { requiresAuth: true } },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("token");
 if (to.meta.requiresAuth && !token) next("/auth");
  else next();
});

export default router;
