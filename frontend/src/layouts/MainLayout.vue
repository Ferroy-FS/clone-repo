<template>
  <div class="layout">
    <aside class="sidebar" :class="{ collapsed: isCollapsed }">
      <div class="brand">
        <div class="logo">💪</div>
        <div v-if="!isCollapsed" class="brand-text">
          <h2>Fitnezz</h2>
          <p>Gym Management</p>
        </div>
      </div>
      <nav class="menu">
        <router-link to="/admin/dashboard" class="menu-item">
          <span>📊</span>
          <span v-if="!isCollapsed">Dashboard</span>
        </router-link>

        <router-link to="/admin/members" class="menu-item">
          <span>👥</span>
          <span v-if="!isCollapsed">Members</span>
        </router-link>

        <router-link to="/admin/trainers" class="menu-item">
          <span>🏃</span>
          <span v-if="!isCollapsed">Trainers</span>
        </router-link>

        <router-link to="/admin/bookings" class="menu-item">
          <span>📅</span>
          <span v-if="!isCollapsed">Bookings</span>
        </router-link>

        <router-link v-if="user.role_id != 2" to="/admin/payments" class="menu-item">
          <span>💳</span>
          <span v-if="!isCollapsed">Payments</span>
        </router-link>

        <router-link to="/admin/attendance" class="menu-item">
          <span>🕒</span>
          <span v-if="!isCollapsed">Attendance</span>
        </router-link>

        <router-link v-if="user.role_id != 3" to="/admin/trainer-earnings" class="menu-item">
          <span>💰</span>
          <span v-if="!isCollapsed">Trainer Earnings</span>
        </router-link>
      </nav>
      <button class="collapse-btn" @click="toggleSidebar">
        {{ isCollapsed ? '→' : '←' }}
      </button>
    </aside>

    <div class="main-section">
      <header class="navbar">
        <div>
          <h1>{{ pageTitle }}</h1>
          <p>Welcome back, {{ user.full_name || 'User' }}</p>
        </div>
        <div class="nav-actions">
          <div class="user-box">
            <div class="avatar">
              {{ user.full_name ? user.full_name.charAt(0).toUpperCase() : 'U' }}
            </div>
            <div class="user-info">
              <strong>{{ user.full_name || 'User' }}</strong>
              <small>{{ roleLabel }}</small>
            </div>
          </div>
          <button class="logout-btn" @click="logout">Logout</button>
        </div>
      </header>
      <main class="page-content">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isCollapsed: false,
      user: JSON.parse(localStorage.getItem('user') || '{}')
    }
  },
  computed: {
    pageTitle() {
      return this.$route.name || 'Dashboard'
    },
    roleLabel() {
      if (this.user.role_id == 1) return 'Administrator'
      if (this.user.role_id == 2) return 'Trainer'
      if (this.user.role_id == 3) return 'Member'
      return 'User'
    }
  },
  methods: {
    toggleSidebar() {
      this.isCollapsed = !this.isCollapsed
    },
    logout() {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      this.$router.push('/login')
    }
  }
}
</script>

<style scoped>
.layout { display: flex; min-height: 100vh; background: #F4E8E3; }
.sidebar { width: 260px; background: #000; color: white; display: flex; flex-direction: column; padding: 24px 16px; transition: all 0.3s ease; box-shadow: 4px 0 20px rgba(0,0,0,0.2); }
.sidebar.collapsed { width: 90px; }
.brand { display: flex; align-items: center; gap: 14px; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.08); }
.logo { width: 52px; height: 52px; border-radius: 16px; background: #DB854F; display: flex; justify-content: center; align-items: center; font-size: 24px; }
.brand-text h2 { margin: 0; font-size: 20px; color: #DB854F; }
.brand-text p { margin: 2px 0 0; color: rgba(255,255,255,0.4); font-size: 13px; }
.menu { display: flex; flex-direction: column; gap: 8px; flex: 1; }
.menu-item { display: flex; align-items: center; gap: 14px; padding: 12px 16px; border-radius: 12px; text-decoration: none; color: rgba(255,255,255,0.6); font-weight: 500; font-size: 14px; transition: all 0.25s ease; }
.menu-item:hover { background: rgba(219,133,79,0.15); color: #DB854F; transform: translateX(4px); }
.router-link-active { background: #DB854F; color: white; }
.router-link-active:hover { background: #c4723e; color: white; transform: none; }
.collapse-btn { margin-top: 20px; border: none; border-radius: 12px; padding: 12px; background: rgba(255,255,255,0.06); color: white; cursor: pointer; font-size: 16px; transition: 0.2s; }
.collapse-btn:hover { background: rgba(219,133,79,0.2); }
.main-section { flex: 1; display: flex; flex-direction: column; background: #F4E8E3; }
.navbar { background: white; padding: 20px 28px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border-bottom: 1px solid rgba(0,0,0,0.06); }
.navbar h1 { margin: 0; font-size: 24px; color: #000; }
.navbar p { margin: 4px 0 0; color: #64748b; font-size: 14px; }
.nav-actions { display: flex; align-items: center; gap: 14px; }
.user-box { display: flex; align-items: center; gap: 12px; background: #F4E8E3; padding: 10px 14px; border-radius: 14px; }
.avatar { width: 40px; height: 40px; border-radius: 50%; background: #DB854F; color: white; display: flex; justify-content: center; align-items: center; font-weight: bold; font-size: 16px; }
.user-info strong { display: block; color: #000; font-size: 14px; }
.user-info small { color: #64748b; font-size: 12px; }
.logout-btn { border: none; padding: 10px 18px; border-radius: 10px; background: #DB854F; color: white; font-weight: 600; cursor: pointer; transition: 0.2s; font-size: 14px; }
.logout-btn:hover { background: #c4723e; }
.page-content { padding: 30px; flex: 1; }
@media (max-width: 768px) {
  .layout { flex-direction: column; }
  .sidebar { width: 100%; flex-direction: row; overflow-x: auto; padding: 14px; }
  .sidebar.collapsed { width: 100%; }
  .brand { display: none; }
  .menu { flex-direction: row; }
  .collapse-btn { display: none; }
  .navbar { flex-direction: column; align-items: flex-start; gap: 14px; }
  .nav-actions { width: 100%; justify-content: space-between; }
}
</style>