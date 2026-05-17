<template>
  <div class="page">
    <div class="hero">
      <div>
        <h1>Dashboard</h1>
        <p class="welcome">Welcome back, {{ user.full_name || 'User' }}</p>
        <p class="role">{{ roleName }}</p>
      </div>
    </div>
    <div class="cards">
      <div class="card" @click="$router.push('/admin/members')">
        <div class="card-icon">👥</div>
        <h3>Members</h3>
        <p>Manage and view gym members</p>
      </div>
      <div class="card" @click="$router.push('/admin/trainers')">
        <div class="card-icon">🏃</div>
        <h3>Trainers</h3>
        <p>See trainer profiles and specialties</p>
      </div>
      <div class="card" @click="$router.push('/admin/bookings')">
        <div class="card-icon">📅</div>
        <h3>Bookings</h3>
        <p>Track trainer booking sessions</p>
      </div>
      <div class="card" @click="$router.push('/admin/payments')">
        <div class="card-icon">💳</div>
        <h3>Payments</h3>
        <p>View payment records and status</p>
      </div>
      <div class="card" @click="$router.push('/admin/attendance')">
        <div class="card-icon">🕒</div>
        <h3>Attendance</h3>
        <p>Monitor check-in and check-out logs</p>
      </div>
      <div class="card" @click="$router.push('/admin/trainer-earnings')">
        <div class="card-icon">💰</div>
        <h3>Trainer Earnings</h3>
        <p>Track mentoring income from sessions</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: JSON.parse(localStorage.getItem('user') || '{}')
    }
  },
  computed: {
    roleName() {
      if (this.user.role_id == 1) return 'Administrator'
      if (this.user.role_id == 2) return 'Trainer'
      return 'Member'
    }
  }
}
</script>

<style scoped>
.page { padding: 30px; }
.hero { background: #000; color: white; padding: 32px; border-radius: 20px; margin-bottom: 28px; box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
.hero h1 { margin: 0; font-size: 30px; color: white; }
.welcome { margin-top: 8px; font-size: 16px; color: rgba(255,255,255,0.6); }
.role { margin-top: 6px; color: #DB854F; font-weight: 600; font-size: 14px; }
.cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; }
.card { background: white; border-radius: 20px; padding: 24px; cursor: pointer; box-shadow: 0 4px 20px rgba(0,0,0,0.06); transition: all 0.25s ease; border: 1px solid rgba(0,0,0,0.04); border-top: 3px solid #DB854F; }
.card:hover { transform: translateY(-5px); box-shadow: 0 12px 32px rgba(219,133,79,0.15); }
.card-icon { font-size: 28px; margin-bottom: 12px; }
.card h3 { margin-bottom: 8px; color: #000; font-size: 17px; }
.card p { color: #64748b; font-size: 14px; line-height: 1.5; }
</style>
