<template>
  <div class="auth-page">
    <div class="auth-card">

      <div class="success-circle">
        <span class="success-check">✓</span>
      </div>

      <p class="welcome-label">PASSWORD CHANGED</p>
      <h1 class="auth-title">Successfully Updated!</h1>
      <div class="divider"></div>

      <div class="role-badge">{{ roleIcon }} {{ role }}</div>

      <p class="auth-sub">
        Hello, <strong>{{ name }}</strong>!<br>
        Your password has been successfully updated.<br>
        Redirecting to login in <strong>{{ timer }}s</strong>...
      </p>

      <div class="progress-bar">
        <div class="progress-fill" :style="{ width: progressPercent + '%' }"></div>
      </div>

      <button class="btn-primary" @click="goToLogin">Go to Login →</button>

    </div>
  </div>
</template>

<script>
export default {
  name: 'SuccessPassword',
  data() {
    return {
      name: '',
      role: '',
      timer: 5,
      interval: null
    }
  },
  computed: {
    roleIcon() {
      if (this.role === 'Admin') return '👑'
      if (this.role === 'Trainer') return '🏃'
      return '💪'
    },
    progressPercent() { return ((5 - this.timer) / 5) * 100 }
  },
  mounted() {
    this.name = this.$route.query.name || 'User'
    this.role = this.$route.query.role || 'Member'
    this.interval = setInterval(() => {
      this.timer--
      if (this.timer <= 0) {
        clearInterval(this.interval)
        this.goToLogin()
      }
    }, 1000)
  },
  beforeUnmount() { clearInterval(this.interval) },
  methods: {
    goToLogin() {
      clearInterval(this.interval)
      this.$router.push('/login')
    }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500;600&display=swap');
* { box-sizing: border-box; }
.auth-page {
  min-height: 100vh; background: #F4E8E3;
  display: flex; align-items: center; justify-content: center;
  padding: 20px; font-family: 'Inter', system-ui, sans-serif;
}
.auth-card {
  background: white; border-radius: 24px; padding: 36px 32px;
  width: 100%; max-width: 380px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.08);
  text-align: center;
}
.success-circle {
  width: 72px; height: 72px; border-radius: 50%;
  background: linear-gradient(135deg, #22c55e, #16a34a);
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 20px;
  box-shadow: 0 8px 24px rgba(34,197,94,0.25);
  animation: pop 0.5s ease;
}
@keyframes pop {
  0% { transform: scale(0); }
  70% { transform: scale(1.1); }
  100% { transform: scale(1); }
}
.success-check { font-size: 32px; color: white; font-weight: 700; }
.welcome-label {
  font-size: 11px; font-weight: 600; letter-spacing: 2.5px;
  color: #DB854F; margin-bottom: 6px; text-transform: uppercase;
}
.auth-title {
  font-family: 'Playfair Display', serif;
  font-size: 26px; font-weight: 700; color: #0f172a;
  margin-bottom: 14px; line-height: 1.2;
}
.divider {
  width: 40px; height: 3px; background: #DB854F;
  border-radius: 3px; margin: 0 auto 18px;
}
.role-badge {
  display: inline-block;
  background: rgba(219,133,79,0.1); color: #DB854F;
  border: 1px solid rgba(219,133,79,0.25);
  padding: 5px 16px; border-radius: 20px;
  font-size: 13px; font-weight: 600; margin-bottom: 16px;
}
.auth-sub {
  font-size: 13px; color: #64748b;
  margin-bottom: 20px; line-height: 1.8;
}
.progress-bar {
  height: 5px; background: #e5e7eb;
  border-radius: 5px; margin-bottom: 20px; overflow: hidden;
}
.progress-fill {
  height: 100%; background: #DB854F; border-radius: 5px;
  transition: width 1s linear;
}
.btn-primary {
  width: 100%; padding: 12px; background: #DB854F; color: white;
  border: none; border-radius: 12px; font-size: 15px; font-weight: 600;
  font-family: 'Inter', system-ui, sans-serif; cursor: pointer;
  transition: all 0.2s; letter-spacing: 0.3px;
}
.btn-primary:hover {
  background: #c4723e; transform: translateY(-1px);
  box-shadow: 0 6px 16px rgba(219,133,79,0.3);
}
@media (max-width: 480px) { .auth-card { padding: 28px 20px; } }
</style>