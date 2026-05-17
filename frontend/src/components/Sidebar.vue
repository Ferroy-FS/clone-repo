<template>
  <aside class="sidebar">
    <div class="logo">
      <h2>🏋 Gym System</h2>
      <p>Fitness Management</p>
    </div>

    <nav class="nav-links">
      <!-- ALL USERS -->
      <router-link to="/admin/dashboard" class="nav-item">
        🏠 Dashboard
      </router-link>

      <!-- ADMIN ONLY -->
      <router-link
        v-if="user.role_id === 1"
        to="/admin/members"
        class="nav-item"
      >
        👥 Members
      </router-link>

      <router-link
        v-if="user.role_id === 1"
        to="/admin/trainers"
        class="nav-item"
      >
        💪 Trainers
      </router-link>

      <router-link
        v-if="user.role_id === 1"
        to="/admin/bookings"
        class="nav-item"
      >
        📅 Bookings
      </router-link>

      <router-link
        v-if="user.role_id === 1"
        to="/admin/payments"
        class="nav-item"
      >
        💳 Payments
      </router-link>

      <router-link
        v-if="user.role_id === 1"
        to="/admin/attendance"
        class="nav-item"
      >
        🕒 Attendance
      </router-link>

      <!-- TRAINER ONLY -->
      <router-link
        v-if="user.role_id === 2"
        to="/admin/bookings"
        class="nav-item"
      >
        📅 My Bookings
      </router-link>

      <router-link
        v-if="user.role_id === 2"
        to="/admin/attendance"
        class="nav-item"
      >
        🕒 My Attendance
      </router-link>

      <!-- MEMBER ONLY -->
      <router-link
        v-if="user.role_id === 3"
        to="/admin/bookings"
        class="nav-item"
      >
        📅 My Bookings
      </router-link>

      <router-link
        v-if="user.role_id === 3"
        to="/admin/payments"
        class="nav-item"
      >
        💳 My Payments
      </router-link>
    </nav>

    <button class="logout-btn" @click="logout">
      🚪 Logout
    </button>
  </aside>
</template>

<script>
export default {
  data() {
    return {
      user: JSON.parse(localStorage.getItem("user") || "{}")
    }
  },
  methods: {
    logout() {
      localStorage.removeItem("token")
      localStorage.removeItem("user")
      this.$router.replace("/")
    }
  }
}
</script>

<style scoped>
.sidebar {
  width: 260px;
  background: #0f172a;
  color: white;
  display: flex;
  flex-direction: column;
  padding: 25px 20px;
  min-height: 100vh;
  box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
}

.logo {
  margin-bottom: 40px;
}

.logo h2 {
  font-size: 24px;
  margin-bottom: 5px;
  color: #22c55e;
}

.logo p {
  font-size: 14px;
  color: #94a3b8;
}

.nav-links {
  display: flex;
  flex-direction: column;
  gap: 12px;
  flex: 1;
}

.nav-item {
  text-decoration: none;
  color: #e2e8f0;
  padding: 14px 16px;
  border-radius: 12px;
  font-weight: 500;
  transition: all 0.25s ease;
}

.nav-item:hover {
  background: #1e293b;
  color: #22c55e;
  transform: translateX(4px);
}

.router-link-active {
  background: #22c55e;
  color: #0f172a !important;
  font-weight: bold;
}

.logout-btn {
  margin-top: 20px;
  padding: 14px;
  border: none;
  border-radius: 12px;
  background: #ef4444;
  color: white;
  font-weight: bold;
  cursor: pointer;
  transition: 0.25s;
}

.logout-btn:hover {
  background: #dc2626;
}
</style>