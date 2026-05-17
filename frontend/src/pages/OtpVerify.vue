<template>
  <div class="auth-page">
    <div class="auth-card">

      <p class="welcome-label">OTP VERIFICATION</p>
      <h1 class="auth-title">Check Your Email</h1>
      <div class="divider"></div>
      <p class="auth-sub">
        We sent a 6-digit OTP code to<br>
        <strong>{{ email }}</strong><br>
        Code expires in <strong>5 minutes</strong>.
      </p>

      <div class="otp-row">
        <input
          v-for="(val, i) in otpDigits" :key="i"
          :ref="'otp' + i"
          v-model="otpDigits[i]"
          class="otp-input" :class="{ filled: otpDigits[i] }"
          type="text" maxlength="1" inputmode="numeric"
          @input="onOtpInput(i)"
          @keydown.backspace="onOtpBack(i)"
        />
      </div>

      <div v-if="error" class="error-box">{{ error }}</div>

      <button class="btn-primary" :disabled="loading || otpCode.length < 6" @click="verifyOtp">
        {{ loading ? 'Verifying...' : 'Verify OTP Code' }}
      </button>

      <div class="bottom-links">
        <button class="link-resend" @click="resendOtp" :disabled="resendTimer > 0">
          {{ resendTimer > 0 ? `Resend in ${resendTimer}s` : 'Resend Code' }}
        </button>
        <router-link to="/forgot-password" class="link-back">← Back</router-link>
      </div>

    </div>
  </div>
</template>

<script>
export default {
  name: 'OtpVerify',
  data() {
    return {
      email: '',
      otpDigits: ['', '', '', '', '', ''],
      error: '',
      loading: false,
      resendTimer: 0,
      resendInterval: null
    }
  },
  computed: {
    otpCode() { return this.otpDigits.join('') }
  },
  mounted() {
    this.email = this.$route.query.email || ''
    if (!this.email) this.$router.push('/forgot-password')
    this.startResendTimer()
    this.$nextTick(() => {
      const first = this.$refs['otp0']
      if (first && first[0]) first[0].focus()
    })
  },
  methods: {
    async verifyOtp() {
      if (this.otpCode.length < 6) {
        this.error = 'Please enter all 6 digits!'
        return
      }
      this.loading = true
      this.error = ''
      try {
        const res = await fetch('/api/otp/forgot/verify', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
          body: JSON.stringify({ email: this.email, code: this.otpCode })
        })
        const data = await res.json()
        if (!data.status) {
          this.error = data.message || 'Invalid or expired OTP code!'
          return
        }
        this.$router.push({ path: '/new-password', query: { email: this.email } })
      } catch {
        this.error = 'Server error. Please try again.'
      } finally {
        this.loading = false
      }
    },
    async resendOtp() {
      this.loading = true
      this.error = ''
      try {
        await fetch('/api/otp/forgot/send', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
          body: JSON.stringify({ email: this.email })
        })
        this.otpDigits = ['', '', '', '', '', '']
        this.startResendTimer()
      } catch {
        this.error = 'Failed to resend OTP.'
      } finally {
        this.loading = false
      }
    },
    onOtpInput(index) {
      if (this.otpDigits[index] && index < 5) {
        this.$nextTick(() => {
          const next = this.$refs['otp' + (index + 1)]
          if (next && next[0]) next[0].focus()
        })
      }
    },
    onOtpBack(index) {
      if (!this.otpDigits[index] && index > 0) {
        this.$nextTick(() => {
          const prev = this.$refs['otp' + (index - 1)]
          if (prev && prev[0]) prev[0].focus()
        })
      }
    },
    startResendTimer() {
      this.resendTimer = 60
      clearInterval(this.resendInterval)
      this.resendInterval = setInterval(() => {
        this.resendTimer--
        if (this.resendTimer <= 0) clearInterval(this.resendInterval)
      }, 1000)
    }
  },
  beforeUnmount() { clearInterval(this.resendInterval) }
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
.welcome-label {
  font-size: 11px; font-weight: 600; letter-spacing: 2.5px;
  color: #DB854F; margin-bottom: 6px; text-transform: uppercase;
}
.auth-title {
  font-family: 'Playfair Display', serif;
  font-size: 28px; font-weight: 700; color: #0f172a;
  margin-bottom: 14px; line-height: 1.2;
}
.divider {
  width: 40px; height: 3px; background: #DB854F;
  border-radius: 3px; margin: 0 auto 20px;
}
.auth-sub {
  font-size: 13px; color: #64748b;
  margin-bottom: 24px; line-height: 1.7;
}
.otp-row {
  display: flex; gap: 10px;
  justify-content: center; margin-bottom: 20px;
}
.otp-input {
  width: 50px; height: 56px; text-align: center;
  border: 2px solid #e5e7eb; border-radius: 12px;
  font-size: 22px; font-weight: 700;
  font-family: 'Inter', monospace;
  outline: none; transition: all 0.2s;
  color: #DB854F; background: #fafafa;
}
.otp-input:focus {
  border-color: #DB854F; background: white;
  box-shadow: 0 0 0 3px rgba(219,133,79,0.1);
}
.otp-input.filled {
  border-color: #DB854F;
  background: rgba(219,133,79,0.04);
}
.error-box {
  background: #fef2f2; border: 1px solid #fecaca;
  color: #dc2626; border-radius: 10px;
  padding: 10px 12px; font-size: 13px;
  margin-bottom: 14px; font-weight: 500;
}
.btn-primary {
  width: 100%; padding: 12px; background: #DB854F;
  color: white; border: none; border-radius: 12px;
  font-size: 15px; font-weight: 600;
  font-family: 'Inter', system-ui, sans-serif;
  cursor: pointer; transition: all 0.2s;
  letter-spacing: 0.3px; margin-bottom: 16px;
}
.btn-primary:hover:not(:disabled) {
  background: #c4723e; transform: translateY(-1px);
  box-shadow: 0 6px 16px rgba(219,133,79,0.3);
}
.btn-primary:disabled { opacity: 0.55; cursor: not-allowed; transform: none; }
.bottom-links {
  display: flex; justify-content: space-between; align-items: center;
}
.link-resend {
  font-size: 13px; color: #365A82; background: none;
  border: none; cursor: pointer; font-weight: 500;
  font-family: 'Inter', system-ui, sans-serif; padding: 0;
  transition: color 0.2s;
}
.link-resend:hover:not(:disabled) { color: #DB854F; }
.link-resend:disabled { color: #94a3b8; cursor: not-allowed; }
.link-back {
  font-size: 13px; color: #94a3b8;
  text-decoration: none; transition: color 0.2s;
}
.link-back:hover { color: #64748b; }
@media (max-width: 480px) {
  .auth-card { padding: 28px 20px; }
  .otp-input { width: 42px; height: 48px; font-size: 18px; }
}
</style>