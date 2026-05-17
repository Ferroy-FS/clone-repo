<template>
  <div class="auth-page">
    <div class="auth-card">
      <p class="welcome-label">WELCOME TO</p>
      <h1 class="auth-title">Login Fitnezz</h1>
      <div class="divider"></div>

      <div class="form-group">
        <label>Email</label>
        <input v-model="email" type="email" placeholder="Enter your email" @keyup.enter="login" />
      </div>

      <div class="form-group">
        <label>Password</label>
        <div class="input-wrap">
          <input v-model="password" :type="showPass ? 'text' : 'password'" placeholder="Enter your password" @keyup.enter="login" />
          <button class="eye-btn" @click="showPass = !showPass" type="button">
            {{ showPass ? '🙈' : '👁️' }}
          </button>
        </div>
      </div>

      <div v-if="error" class="error-box">{{ error }}</div>

      <button class="btn-primary" :disabled="loading" @click="login">
        {{ loading ? 'Logging in...' : 'Login' }}
      </button>

      <div class="bottom-links">
        <router-link to="/forgot-password" class="link-forgot">Forgot Password?</router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Login',
  data() {
    return {
      email: '',
      password: '',
      showPass: false,
      error: '',
      loading: false
    }
  },
  methods: {
    async login() {
      if (!this.email || !this.password) {
        this.error = 'Email and password are required!'
        return
      }
      this.loading = true
      this.error = ''
      try {
        const res = await fetch('/api/auth/login', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
          body: JSON.stringify({ email: this.email, password: this.password })
        })
        const data = await res.json()
        if (!data.status) {
          this.error = data.message || 'Invalid email or password!'
          return
        }
        localStorage.setItem('token', data.token)
        localStorage.setItem('user', JSON.stringify(data.user))
        this.$router.push('/admin/dashboard')
      } catch {
        this.error = 'Server error. Please try again.'
      } finally {
        this.loading = false
      }
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
}
.welcome-label {
  font-size: 11px; font-weight: 600; letter-spacing: 2.5px;
  color: #DB854F; text-align: center; margin-bottom: 6px;
  text-transform: uppercase;
}
.auth-title {
  font-family: 'Playfair Display', serif;
  font-size: 28px; font-weight: 700; color: #0f172a;
  text-align: center; margin-bottom: 14px; line-height: 1.2;
}
.divider {
  width: 40px; height: 3px; background: #DB854F;
  border-radius: 3px; margin: 0 auto 24px;
}
.form-group { margin-bottom: 14px; }
.form-group label {
  display: block; font-size: 12px; font-weight: 600;
  color: #374151; margin-bottom: 6px;
  letter-spacing: 0.3px; text-transform: uppercase;
}
.form-group input {
  width: 100%; padding: 11px 14px;
  border: 1.5px solid #e5e7eb; border-radius: 12px;
  font-size: 14px; font-family: 'Inter', system-ui, sans-serif;
  outline: none; transition: all 0.2s; color: #0f172a; background: #fafafa;
}
.form-group input:focus {
  border-color: #DB854F; background: white;
  box-shadow: 0 0 0 3px rgba(219,133,79,0.08);
}
.form-group input::placeholder { color: #9ca3af; }
.input-wrap { position: relative; }
.input-wrap input { padding-right: 44px; }
.eye-btn {
  position: absolute; right: 12px; top: 50%;
  transform: translateY(-50%);
  background: none; border: none; cursor: pointer; font-size: 15px; padding: 4px;
}
.error-box {
  background: #fef2f2; border: 1px solid #fecaca; color: #dc2626;
  border-radius: 10px; padding: 10px 12px; font-size: 13px;
  margin-bottom: 14px; font-weight: 500;
}
.btn-primary {
  width: 100%; padding: 12px; background: #DB854F; color: white;
  border: none; border-radius: 12px; font-size: 15px; font-weight: 600;
  font-family: 'Inter', system-ui, sans-serif; cursor: pointer;
  transition: all 0.2s; letter-spacing: 0.3px; margin-bottom: 16px;
}
.btn-primary:hover:not(:disabled) {
  background: #c4723e; transform: translateY(-1px);
  box-shadow: 0 6px 16px rgba(219,133,79,0.3);
}
.btn-primary:disabled { opacity: 0.55; cursor: not-allowed; transform: none; }
.bottom-links { display: flex; justify-content: center; }
.link-forgot {
  font-size: 13px; color: #365A82;
  text-decoration: none; font-weight: 500; transition: color 0.2s;
}
.link-forgot:hover { color: #DB854F; }
@media (max-width: 480px) {
  .auth-card { padding: 28px 20px; }
  .auth-title { font-size: 24px; }
}
</style>