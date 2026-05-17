<template>
  <header class="navbar">
    <div>
      <h1>Gym Management System</h1>
      <p>Selamat Datang, {{ user.full_name || 'User' }}</p>
    </div>

    <div class="user-box">
      <div class="avatar">
        {{ firstLetter }}
      </div>
      <div class="user-info">
        <strong>{{ user.full_name || 'User' }}</strong>
        <span>{{ roleName }}</span>
      </div>
    </div>
  </header>
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
      if (this.user.role_id == 1) return 'Admin'
      if (this.user.role_id == 2) return 'Trainer'
      return 'Member'
    },
    firstLetter() {
      return this.user.full_name ? this.user.full_name.charAt(0).toUpperCase() : 'U'
    }
  }
}
</script>

<style scoped>
.navbar {
  background: white;
  padding: 20px 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 2px 12px rgba(0,0,0,0.05);
  border-bottom: 1px solid #e2e8f0;
}

.navbar h1 {
  margin: 0;
  color: #0f172a;
  font-size: 28px;
}

.navbar p {
  margin-top: 5px;
  color: #64748b;
}

.user-box {
  display: flex;
  align-items: center;
  gap: 12px;
}

.avatar {
  width: 45px;
  height: 45px;
  border-radius: 50%;
  background: #22c55e;
  color: #0f172a;
  display: flex;
  justify-content: center;
  align-items: center;
  font-weight: bold;
  font-size: 20px;
}

.user-info {
  display: flex;
  flex-direction: column;
}

.user-info strong {
  color: #0f172a;
}

.user-info span {
  color: #64748b;
  font-size: 14px;
}
</style>