import { createRouter, createWebHistory } from 'vue-router'
import MainLayout from '../layouts/MainLayout.vue'
import LandingPage from '../pages/LandingPage.vue'
import Login from '../pages/Login.vue'
import ForgotPassword from '../pages/ForgotPassword.vue'
import OtpVerify from '../pages/OtpVerify.vue'
import NewPassword from '../pages/NewPassword.vue'
import SuccessPassword from '../pages/SuccessPassword.vue'
import Dashboard from '../pages/Dashboard.vue'
import Members from '../pages/Members.vue'
import Payments from '../pages/Payments.vue'
import Booking from '../pages/Booking.vue'
import Trainer from '../pages/Trainers.vue'
import Attendance from '../pages/Attendance.vue'
import TrainerEarnings from '../pages/TrainerEarnings.vue'

const routes = [
  {
    path: '/',
    name: 'Landing',
    component: LandingPage,
    meta: { guestOnly: true }
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { guestOnly: true }
  },
  { path: '/forgot-password', 
    name: 'ForgotPassword', 
    component: ForgotPassword },
  { path: '/otp-verify', 
    name: 'OtpVerify', 
    component: OtpVerify },
  { path: '/new-password', 
    name: 'NewPassword', 
    component: NewPassword },
  { path: '/success-password', 
    name: 'SuccessPassword', 
    component: SuccessPassword },
  {
    path: '/admin',
    component: MainLayout,
    meta: { requiresAuth: true },
    children: [
      { path: '', redirect: '/admin/dashboard' },
      { path: 'dashboard', name: 'Dashboard', component: Dashboard },
      { path: 'members', name: 'Members', component: Members },
      { path: 'payments', name: 'Payments', component: Payments },
      { path: 'bookings', name: 'Bookings', component: Booking },
      { path: 'trainers', name: 'Trainers', component: Trainer },
      { path: 'trainer-earnings', name: 'TrainerEarnings', component: TrainerEarnings },
      { path: 'attendance', name: 'Attendance', component: Attendance }
    ]
  },
  {
    path: '/:pathMatch(.*)*',
    redirect: '/'
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from) => {
  const token = localStorage.getItem('token')

  // Private page — no token → go to login
  if (to.meta.requiresAuth && !token) {
    return '/login'
  }

  // Guest only page — has token → go to dashboard
  if (to.meta.guestOnly && token) {
    return '/admin/dashboard'
  }
})
export default router

