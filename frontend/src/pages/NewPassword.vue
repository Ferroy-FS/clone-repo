<template>
  <div class="auth-page">
    <div class="auth-card">

      <p class="welcome-label">NEW PASSWORD</p>
      <h1 class="auth-title">Create New Password</h1>
      <div class="divider"></div>
      <p class="auth-sub">Create a strong password for <strong>{{ email }}</strong></p>

      <div class="form-group">
        <label>New Password</label>
        <div class="input-wrap">
          <input v-model="password" :type="showPass ? 'text' : 'password'" placeholder="Min. 8 characters" maxlength="15" />
          <button class="eye-btn" @click="showPass = !showPass" type="button">{{ showPass ? '🙈' : '👁️' }}</button>
        </div>
        <div v-if="password" class="strength-wrap">
          <div class="strength-bar">
            <div class="strength-fill" :style="{ width: strengthPercent + '%', background: strengthColor }"></div>
          </div>
          <span class="strength-label" :style="{ color: strengthColor }">{{ strengthLabel }}</span>
        </div>
        <div v-if="password" class="rules-list">
          <div class="rule" :class="rule8to15 ? 'ok' : 'fail'">{{ rule8to15 ? '✅' : '❌' }} 8–15 characters ({{ password.length }}/15)</div>
          <div class="rule" :class="ruleUpper ? 'ok' : 'fail'">{{ ruleUpper ? '✅' : '❌' }} At least 1 uppercase (A-Z)</div>
          <div class="rule" :class="ruleLower ? 'ok' : 'fail'">{{ ruleLower ? '✅' : '❌' }} At least 1 lowercase (a-z)</div>
          <div class="rule" :class="ruleNumber ? 'ok' : 'fail'">{{ ruleNumber ? '✅' : '❌' }} At least 1 number (0-9)</div>
          <div class="rule" :class="ruleSpecial ? 'ok' : 'fail'">{{ ruleSpecial ? '✅' : '❌' }} At least 1 special char (#?!@)</div>
        </div>
      </div>

      <div class="form-group">
        <label>Confirm Password</label>
        <div class="input-wrap">
          <input v-model="confirmPassword" :type="showConfirm ? 'text' : 'password'" placeholder="Repeat new password" maxlength="15" @keyup.enter="resetPassword" />
          <button class="eye-btn" @click="showConfirm = !showConfirm" type="button">{{ showConfirm ? '🙈' : '👁️' }}</button>
        </div>
        <div v-if="confirmPassword && password !== confirmPassword" class="match-error">❌ Passwords do not match</div>
        <div v-if="confirmPassword && password === confirmPassword" class="match-ok">✅ Passwords match</div>
      </div>

      <div v-if="error" class="error-box">{{ error }}</div>

      <button class="btn-primary" :disabled="loading || !isValid" @click="resetPassword">
        {{ loading ? 'Saving...' : 'Save New Password' }}
      </button>

      <div class="hint-box">
        💡 Example: <code>Ali@1</code> or <code>Qamar!2026Ab</code>
      </div>

    </div>
  </div>
</template>

<script>
export default {
  name: 'NewPassword',
  data() {
    return {
      email: '',
      password: '',
      confirmPassword: '',
      showPass: false,
      showConfirm: false,
      error: '',
      loading: false
    }
  },
  computed: {
    rule8to15() { return this.password.length >= 8 && this.password.length <= 15 },
    ruleUpper()  { return /[A-Z]/.test(this.password) },
    ruleLower()  { return /[a-z]/.test(this.password) },
    ruleNumber() { return /[0-9]/.test(this.password) },
    ruleSpecial(){ return /[#?!@]/.test(this.password) },
    allRulesPassed() {
      return this.rule8to15 && this.ruleUpper && this.ruleLower && this.ruleNumber && this.ruleSpecial
    },
    isValid() {
      return this.allRulesPassed && this.password === this.confirmPassword && this.confirmPassword.length > 0
    },
    strengthPercent() {
      let s = 0
      if (this.rule8to15)   s += 20
      if (this.ruleUpper)   s += 20
      if (this.ruleLower)   s += 20
      if (this.ruleNumber)  s += 20
      if (this.ruleSpecial) s += 20
      return s
    },
    strengthColor() {
      if (this.strengthPercent <= 20) return '#ef4444'
      if (this.strengthPercent <= 40) return '#f97316'
      if (this.strengthPercent <= 60) return '#eab308'
      if (this.strengthPercent <= 80) return '#22c55e'
      return '#16a34a'
    },
    strengthLabel() {
      if (this.strengthPercent <= 20) return '😟 Very Weak'
      if (this.strengthPercent <= 40) return '😐 Weak'
      if (this.strengthPercent <= 60) return '🙂 Fair'
      if (this.strengthPercent <= 80) return '😊 Strong'
      return '💪 Very Strong'
    }
  },
  mounted() {
    this.email = this.$route.query.email || ''
    if (!this.email) this.$router.push('/forgot-password')
  },
  methods: {
    async resetPassword() {
      if (!this.isValid) return
      this.loading = true
      this.error = ''
      try {
        const res = await fetch('/api/otp/reset', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
          body: JSON.stringify({
            email: this.email,
            password: this.password,
            password_confirmation: this.confirmPassword
          })
        })
        const data = await res.json()
        if (!data.status) {
          this.error = data.message || 'Failed to reset password!'
          return
        }
        this.$router.push({
          path: '/success-password',
          query: { name: data.user_name, role: data.role }
        })
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
  width: 100%; max-width: 400px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.08);
}
.welcome-label {
  font-size: 11px; font-weight: 600; letter-spacing: 2.5px;
  color: #DB854F; text-align: center; margin-bottom: 6px; text-transform: uppercase;
}
.auth-title {
  font-family: 'Playfair Display', serif;
  font-size: 26px; font-weight: 700; color: #0f172a;
  text-align: center; margin-bottom: 14px; line-height: 1.2;
}
.divider {
  width: 40px; height: 3px; background: #DB854F;
  border-radius: 3px; margin: 0 auto 18px;
}
.auth-sub {
  font-size: 13px; color: #64748b; text-align: center;
  margin-bottom: 20px; line-height: 1.6;
}
.form-group { margin-bottom: 14px; }
.form-group label {
  display: block; font-size: 12px; font-weight: 600;
  color: #374151; margin-bottom: 6px;
  letter-spacing: 0.3px; text-transform: uppercase;
}
.input-wrap { position: relative; }
.input-wrap input {
  width: 100%; padding: 11px 44px 11px 14px;
  border: 1.5px solid #e5e7eb; border-radius: 12px;
  font-size: 14px; font-family: 'Inter', system-ui, sans-serif;
  outline: none; transition: all 0.2s; color: #0f172a; background: #fafafa;
}
.input-wrap input:focus {
  border-color: #DB854F; background: white;
  box-shadow: 0 0 0 3px rgba(219,133,79,0.08);
}
.input-wrap input::placeholder { color: #9ca3af; }
.eye-btn {
  position: absolute; right: 12px; top: 50%;
  transform: translateY(-50%);
  background: none; border: none; cursor: pointer; font-size: 15px; padding: 4px;
}
.strength-wrap {
  display: flex; align-items: center; gap: 10px; margin-top: 8px;
}
.strength-bar {
  flex: 1; height: 5px; background: #e5e7eb;
  border-radius: 5px; overflow: hidden;
}
.strength-fill { height: 100%; border-radius: 5px; transition: all 0.3s; }
.strength-label { font-size: 11px; font-weight: 600; white-space: nowrap; }
.rules-list {
  margin-top: 8px; background: #f8fafc;
  border-radius: 10px; padding: 10px 12px;
  display: flex; flex-direction: column; gap: 4px;
}
.rule { font-size: 12px; font-weight: 500; }
.rule.ok { color: #16a34a; }
.rule.fail { color: #ef4444; }
.match-error { font-size: 12px; color: #ef4444; margin-top: 5px; font-weight: 500; }
.match-ok { font-size: 12px; color: #16a34a; margin-top: 5px; font-weight: 500; }
.error-box {
  background: #fef2f2; border: 1px solid #fecaca; color: #dc2626;
  border-radius: 10px; padding: 10px 12px; font-size: 13px;
  margin-bottom: 14px; font-weight: 500;
}
.btn-primary {
  width: 100%; padding: 12px; background: #DB854F; color: white;
  border: none; border-radius: 12px; font-size: 15px; font-weight: 600;
  font-family: 'Inter', system-ui, sans-serif; cursor: pointer;
  transition: all 0.2s; letter-spacing: 0.3px; margin-bottom: 14px;
}
.btn-primary:hover:not(:disabled) {
  background: #c4723e; transform: translateY(-1px);
  box-shadow: 0 6px 16px rgba(219,133,79,0.3);
}
.btn-primary:disabled { opacity: 0.55; cursor: not-allowed; transform: none; }
.hint-box {
  background: rgba(54,90,130,0.06); border: 1px solid rgba(54,90,130,0.15);
  border-radius: 10px; padding: 10px 12px;
  font-size: 12px; color: #365A82; line-height: 1.6; text-align: center;
}
.hint-box code {
  background: rgba(54,90,130,0.1); padding: 2px 6px;
  border-radius: 4px; font-family: monospace; font-size: 12px;
}
@media (max-width: 480px) { .auth-card { padding: 28px 20px; } }
</style>