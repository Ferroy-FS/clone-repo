<template>
  <div class="landing">

    <div v-if="showCookieBanner" class="cookie-banner">
      <div class="cookie-content">
        <span class="cookie-icon">🍪</span>
        <p>We use cookies to improve your experience. No data is shared with third parties.</p>
        <div class="cookie-btns">
          <button class="cookie-accept" @click="acceptCookies">Accept</button>
          <button class="cookie-decline" @click="declineCookies">Decline</button>
        </div>
      </div>
    </div>

    <nav class="navbar" :class="{ scrolled: isScrolled }">
      <div class="nav-container">
        <div class="nav-logo">
          <span class="logo-icon">💪</span>
          <span class="logo-text">Fitnezz</span>
        </div>
        <div class="nav-links" :class="{ open: menuOpen }">
          <a href="#home" @click="menuOpen=false">Home</a>
          <a href="#features" @click="menuOpen=false">Features</a>
          <a href="#howto" @click="menuOpen=false">How It Works</a>
          <a href="#services" @click="menuOpen=false">Services</a>
          <a href="#about" @click="menuOpen=false">About</a>
          <router-link to="/login" class="nav-cta" @click="menuOpen=false">Login</router-link>
        </div>
        <button class="hamburger" @click="menuOpen = !menuOpen">
          <span></span><span></span><span></span>
        </button>
      </div>
    </nav>

    <section id="home" class="hero">
      <div class="hero-bg"></div>
      <div class="hero-content">
        <div class="hero-left">
          <div class="hero-badge">🏆 Best Gym Platform</div>
          <h1 class="hero-title">
            Welcome to<br>
            <span class="hero-accent">Fitnezz Gym.</span>
          </h1>
          <p class="hero-sub">
            Fitnezz is a modern gym management app built for everyone 
            whether you are a member looking to book a trainer, a trainer managing
            your schedule.
            Everything you need, all in one place.
          </p>
          <div class="hero-btns">
            <router-link to="/login" class="btn-primary">Get Started</router-link>
            <a href="#howto" class="btn-outline">How It Works</a>
          </div>
          <div class="hero-stats">
            <div class="hero-stat">
              <div class="hero-stat-num">500+</div>
              <div class="hero-stat-label">Active Members</div>
            </div>
            <div class="hero-stat-divider"></div>
            <div class="hero-stat">
              <div class="hero-stat-num">50+</div>
              <div class="hero-stat-label">Expert Trainers</div>
            </div>
            <div class="hero-stat-divider"></div>
            <div class="hero-stat">
              <div class="hero-stat-num">98%</div>
              <div class="hero-stat-label">Member Satisfaction</div>
            </div>
          </div>
        </div>
        <div class="hero-visual">
          <div class="hero-card card-1">
            <div class="hc-icon">✅</div>
            <div class="hc-text">
              <div class="hc-title">Today Attendance</div>
              <div class="hc-sub">47 check-ins recorded</div>
            </div>
          </div>
          <div class="hero-card card-2">
            <div class="hc-icon">💳</div>
            <div class="hc-text">
              <div class="hc-title">Payments</div>
              <div class="hc-sub">Rp 12.5M collected</div>
            </div>
          </div>
          <div class="hero-card card-3">
            <div class="hc-icon">📅</div>
            <div class="hc-text">
              <div class="hc-title">Trainer Bookings</div>
              <div class="hc-sub">12 sessions today</div>
            </div>
          </div>
          <div class="hero-orb orb-1"></div>
          <div class="hero-orb orb-2"></div>
        </div>
      </div>
    </section>

    <section id="features" class="features">
      <div class="section-container">
        <div class="section-badge">Features</div>
        <h2 class="section-title">What Can You Do with Fitnezz?</h2>
        <p class="section-sub">Everything you need for the best gym experience — all in one app</p>
        <div class="features-grid">
          <div class="feature-card" v-for="f in features" :key="f.title">
            <div class="feature-icon">{{ f.icon }}</div>
            <h3>{{ f.title }}</h3>
            <p>{{ f.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <section id="howto" class="howto">
      <div class="section-container">
        <div class="section-badge">How It Works</div>
        <h2 class="section-title howto-title">Get Started in 3 Easy Steps</h2>
        <p class="section-sub howto-sub">Simple, fast, and straightforward — start your fitness journey today</p>
        <div class="steps-grid">
          <div class="step-card">
            <div class="step-num">01</div>
            <div class="step-icon">🔑</div>
            <h3>Log In to Your Account</h3>
            <p>Sign in using your email and password provided by your gym admin. First time? Contact your admin to get access.</p>
          </div>
          <div class="step-arrow">→</div>
          <div class="step-card">
            <div class="step-num">02</div>
            <div class="step-icon">🏋️</div>
            <h3>Choose Your Service</h3>
            <p>Book a personal trainer, record your daily attendance, or manage your membership payment — all in a few clicks.</p>
          </div>
          <div class="step-arrow">→</div>
          <div class="step-card">
            <div class="step-num">03</div>
            <div class="step-icon">📈</div>
            <h3>Track Your Progress</h3>
            <p>View your training history, attendance records, and fitness milestones. Stay on top of your fitness journey anytime.</p>
          </div>
        </div>
      </div>
    </section>

    <section id="services" class="roles">
      <div class="section-container">
        <div class="section-badge">Who Is It For</div>
        <h2 class="section-title">Fitnezz Is Built for Everyone</h2>
        <p class="section-sub">Whatever your role at the gym, Fitnezz makes everything easier for you</p>
        <div class="roles-grid">
          <div class="role-card role-member">
            <div class="role-icon">💪</div>
            <h3>I am a Member</h3>
            <p>Want to book a trainer, track your attendance, or pay your membership? Fitnezz has everything you need.</p>
            <ul>
              <li>✓ Book your favorite personal trainer</li>
              <li>✓ Check-in and check-out at the gym</li>
              <li>✓ Pay via Cash, Bank Transfer, or QRIS</li>
              <li>✓ View your full payment history</li>
              <li>✓ Track your training sessions</li>
            </ul>
            <router-link to="/login" class="role-btn">Login as Member</router-link>
          </div>
          <div class="role-card role-trainer">
            <div class="role-icon">🏃</div>
            <h3>I am a Trainer</h3>
            <p>Manage your session schedule, monitor your members, and track your mentoring earnings with ease.</p>
            <ul>
              <li>✓ View and manage your booking schedule</li>
              <li>✓ Track earnings from every session</li>
              <li>✓ Monitor member progress</li>
              <li>✓ Log training session attendance</li>
              <li>✓ Manage your trainer profile</li>
            </ul>
            <router-link to="/login" class="role-btn">Login as Trainer</router-link>
          </div>
          <div class="role-card role-admin">
            <div class="role-icon">👑</div>
            <h3>I am a Gym Admin</h3>
            <p>Full control over your gym operations — from member data to financial reports, all in one dashboard.</p>
            <ul>
              <li>✓ Add and manage members and trainers</li>
              <li>✓ Monitor all payment transactions</li>
              <li>✓ Track daily attendance of all members</li>
              <li>✓ View trainer earnings reports</li>
              <li>✓ Access full analytics dashboard</li>
            </ul>
            <router-link to="/login" class="role-btn">Login as Admin</router-link>
          </div>
        </div>
      </div>
    </section>

    <section id="about" class="about">
      <div class="section-container about-grid">
        <div class="about-text">
          <div class="section-badge">About Fitnezz</div>
          <h2 class="section-title">Why Choose Fitnezz?</h2>
          <p>Fitnezz is a modern gym management system based in
            <strong>Surabaya, Indonesia</strong>. Built with the latest technology —
            Laravel, Vue.js, PostgreSQL, and Docker — to ensure security,
            speed, and ease of use for every gym user.</p>
          <div class="about-points">
            <div class="about-point">
              <div class="ap-icon">🔒</div>
              <div>
                <strong>Secure and Trusted</strong>
                <p>Your data is protected with bcrypt encryption, token-based authentication, and role-based access control.</p>
              </div>
            </div>
            <div class="about-point">
              <div class="ap-icon">⚡</div>
              <div>
                <strong>Fast and Reliable</strong>
                <p>Powered by Redis caching and PostgreSQL for maximum performance with zero downtime.</p>
              </div>
            </div>
            <div class="about-point">
              <div class="ap-icon">📱</div>
              <div>
                <strong>Access Anywhere, Anytime</strong>
                <p>Works perfectly on desktop, tablet, and smartphone. Stay connected to your gym wherever you are.</p>
              </div>
            </div>
            <div class="about-point">
              <div class="ap-icon">📍</div>
              <div>
                <strong>Based in Surabaya</strong>
                <p>Built and operated in Surabaya, East Java, Indonesia.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="about-visual">
      <div class="about-card">
      <div class="ac-header">💪 Why Our Members Love Us</div>
      <div class="ac-items">
      <div class="ac-item"><span class="ac-dot orange"></span>Easy to use for all ages</div>
      <div class="ac-item"><span class="ac-dot blue"></span>Secure and private data</div>
      <div class="ac-item"><span class="ac-dot lightblue"></span>Available 24/7 anytime</div>
      <div class="ac-item"><span class="ac-dot orange"></span>Fast and smooth experience</div>
      <div class="ac-item"><span class="ac-dot blue"></span>Works on all devices</div>
      <div class="ac-item"><span class="ac-dot dark"></span>Trusted by 500+ members</div>
      </div>
      <div class="ac-footer">
      <span class="ac-badge">Enjoy Your sessions</span>
      <span class="ac-badge">This we made For You</span>
      </div>
     </div>
     </div>
      </div>
    </section>

    <section class="cta">
      <div class="cta-content">
        <h2>Ready to Start Your Fitness Journey?</h2>
        <p>Join hundreds of members and trainers already using Fitnezz in Surabaya.</p>
        <router-link to="/login" class="btn-primary btn-large">Get Started Now</router-link>
      </div>
    </section>

    <footer class="footer">
      <div class="footer-container">
        <div class="footer-brand">
          <div class="nav-logo">
            <span class="logo-icon">💪</span>
            <span class="logo-text">Fitnezz</span>
          </div>
          <p>Modern gym management platform for members and trainers in Surabaya.</p>
          <p class="footer-address">📍 Surabaya, East Java, Indonesia</p>
        </div>
        <div class="footer-links">
          <h4>Menu</h4>
          <a href="#home">Home</a>
          <a href="#features">Features</a>
          <a href="#howto">How It Works</a>
          <a href="#about">About</a>
        </div>
        <div class="footer-links">
          <h4>Access</h4>
          <router-link to="/login">Member Login</router-link>
          <router-link to="/login">Trainer Login</router-link>
          <router-link to="/login">Admin Login</router-link>
        </div>
      </div>
      <div class="footer-bottom">
        <p>2026 Fitnezz Gym Management System — Surabaya, Indonesia. we can build your Muscle not your heart</p>
      </div>
    </footer>

  </div>
</template>

<script>
export default {
  name: 'LandingPage',
  data() {
    return {
      isScrolled: false,
      menuOpen: false,
      showCookieBanner: false,
      features: [
        { icon: '🏋️', title: 'Book a Personal Trainer', desc: 'Choose a trainer based on their specialization and availability. Book your training session directly from this web and app.' },
        { icon: '✅', title: 'Digital Attendance', desc: 'Check-in and check-out at the gym digitally. Your attendance history is recorded automatically and accessible anytime.' },
        { icon: '💳', title: 'Flexible Payments', desc: 'Pay your membership via Cash, Bank Transfer, or QRIS. All payment history is stored neatly in the system.' },
        { icon: '📊', title: 'Complete Dashboard', desc: 'View a summary of your gym activity attendance, training sessions, and payments  all in one informative dashboard.' },
        { icon: '💰', title: 'Trainer Earnings', desc: 'Trainers can transparently monitor their earnings from every completed mentoring session, accurately and in real time.' },
        { icon: '📱', title: 'Access Anywhere', desc: 'Use Fitnezz from anywhere  desktop, tablet, or smartphone. Always stay connected to your gym.' }
      ]
    }
  },
  mounted() {
    window.addEventListener('scroll', this.handleScroll)
    this.checkCookieConsent()
  },
  beforeUnmount() {
    window.removeEventListener('scroll', this.handleScroll)
  },
  methods: {
    handleScroll() { this.isScrolled = window.scrollY > 50 },
    checkCookieConsent() {
      const consent = localStorage.getItem('cookieConsent')
      if (!consent) setTimeout(() => { this.showCookieBanner = true }, 1500)
    },
    acceptCookies() { localStorage.setItem('cookieConsent', 'true'); this.showCookieBanner = false },
    declineCookies() { localStorage.setItem('cookieConsent', 'false'); this.showCookieBanner = false }
  }
}
</script>

<style scoped>
* { box-sizing: border-box; margin: 0; padding: 0; }
.landing { font-family: Georgia, serif; color: #1a1a1a; overflow-x: hidden; }
.cookie-banner { position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); background: #000; color: #fff; border-radius: 16px; padding: 14px 20px; z-index: 9999; width: 90%; max-width: 620px; box-shadow: 0 8px 32px rgba(0,0,0,0.4); }
.cookie-content { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.cookie-icon { font-size: 20px; }
.cookie-content p { flex: 1; font-size: 13px; color: rgba(255,255,255,0.8); font-family: system-ui; }
.cookie-btns { display: flex; gap: 8px; }
.cookie-accept { background: #DB854F; color: #fff; border: none; padding: 7px 16px; border-radius: 8px; cursor: pointer; font-size: 12px; font-weight: 600; }
.cookie-decline { background: transparent; color: rgba(255,255,255,0.6); border: 1px solid rgba(255,255,255,0.3); padding: 7px 16px; border-radius: 8px; cursor: pointer; font-size: 12px; }
.navbar { position: fixed; top: 0; left: 0; right: 0; z-index: 1000; padding: 16px 0; transition: all 0.3s; }
.navbar.scrolled { background: rgba(0,0,0,0.95); backdrop-filter: blur(10px); padding: 10px 0; box-shadow: 0 4px 20px rgba(0,0,0,0.3); }
.nav-container { max-width: 1200px; margin: 0 auto; padding: 0 24px; display: flex; align-items: center; justify-content: space-between; }
.nav-logo { display: flex; align-items: center; gap: 8px; }
.logo-icon { font-size: 22px; }
.logo-text { font-size: 20px; font-weight: 700; color: #DB854F; }
.nav-links { display: flex; align-items: center; gap: 28px; }
.nav-links a { color: rgba(255,255,255,0.8); text-decoration: none; font-size: 14px; font-family: system-ui; transition: color 0.2s; }
.nav-links a:hover { color: #DB854F; }
.nav-cta { background: #DB854F; color: #fff; padding: 8px 20px; border-radius: 8px; font-weight: 600; }
.hamburger { display: none; flex-direction: column; gap: 5px; background: none; border: none; cursor: pointer; padding: 4px; }
.hamburger span { display: block; width: 22px; height: 2px; background: #fff; border-radius: 2px; }
.hero { min-height: 100vh; background: #000; display: flex; align-items: center; padding: 120px 24px 80px; position: relative; overflow: hidden; }
.hero-bg { position: absolute; inset: 0; background: radial-gradient(ellipse at 70% 50%, rgba(54,90,130,0.15) 0%, transparent 60%), radial-gradient(ellipse at 20% 80%, rgba(219,133,79,0.1) 0%, transparent 50%); }
.hero-content { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; width: 100%; position: relative; z-index: 1; }
.hero-badge { display: inline-block; background: rgba(219,133,79,0.15); color: #DB854F; border: 1px solid rgba(219,133,79,0.3); padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 500; margin-bottom: 20px; font-family: system-ui; }
.hero-title { font-size: clamp(36px, 5vw, 60px); color: #fff; line-height: 1.1; margin-bottom: 20px; font-weight: 700; }
.hero-accent { color: #DB854F; }
.hero-sub { color: rgba(255,255,255,0.6); font-size: 16px; line-height: 1.7; margin-bottom: 32px; font-family: system-ui; max-width: 480px; }
.hero-btns { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 40px; }
.btn-primary { background: #DB854F; color: #fff; padding: 13px 28px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 15px; font-family: system-ui; transition: all 0.2s; display: inline-block; }
.btn-primary:hover { background: #c4723e; transform: translateY(-2px); }
.btn-outline { background: transparent; color: #fff; padding: 13px 28px; border-radius: 10px; text-decoration: none; font-size: 15px; border: 1px solid rgba(255,255,255,0.3); font-family: system-ui; transition: all 0.2s; display: inline-block; }
.btn-outline:hover { border-color: #DB854F; color: #DB854F; }
.hero-stats { display: flex; gap: 24px; align-items: center; }
.hero-stat-num { font-size: 26px; font-weight: 700; color: #DB854F; }
.hero-stat-label { font-size: 11px; color: rgba(255,255,255,0.5); font-family: system-ui; margin-top: 2px; }
.hero-stat-divider { width: 1px; height: 36px; background: rgba(255,255,255,0.15); }
.hero-visual { position: relative; height: 400px; display: flex; align-items: center; justify-content: center; }
.hero-card { position: absolute; background: rgba(255,255,255,0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 16px 20px; display: flex; align-items: center; gap: 12px; animation: float 3s ease-in-out infinite; }
.card-1 { top: 20%; left: 5%; animation-delay: 0s; }
.card-2 { top: 45%; right: 5%; animation-delay: 1s; }
.card-3 { bottom: 15%; left: 15%; animation-delay: 2s; }
@keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
.hc-icon { font-size: 24px; }
.hc-title { font-size: 13px; font-weight: 600; color: #fff; font-family: system-ui; }
.hc-sub { font-size: 11px; color: rgba(255,255,255,0.5); font-family: system-ui; }
.hero-orb { position: absolute; border-radius: 50%; filter: blur(60px); }
.orb-1 { width: 300px; height: 300px; background: rgba(54,90,130,0.2); top: 50%; left: 50%; transform: translate(-50%,-50%); }
.orb-2 { width: 200px; height: 200px; background: rgba(219,133,79,0.15); bottom: 10%; right: 10%; }
.section-container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
.section-badge { display: inline-block; background: rgba(219,133,79,0.1); color: #DB854F; border: 1px solid rgba(219,133,79,0.3); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; margin-bottom: 12px; font-family: system-ui; }
.section-title { font-size: clamp(28px, 4vw, 42px); font-weight: 700; margin-bottom: 12px; color: #000; }
.section-sub { color: #64748b; font-size: 16px; margin-bottom: 48px; font-family: system-ui; }
.features { padding: 100px 0; background: #F4E8E3; }
.features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; }
.feature-card { background: #fff; border-radius: 20px; padding: 28px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); transition: all 0.3s; border: 1px solid rgba(0,0,0,0.04); }
.feature-card:hover { transform: translateY(-6px); box-shadow: 0 12px 40px rgba(219,133,79,0.15); border-color: rgba(219,133,79,0.3); }
.feature-icon { font-size: 32px; margin-bottom: 14px; }
.feature-card h3 { font-size: 17px; font-weight: 600; margin-bottom: 8px; color: #000; }
.feature-card p { font-size: 14px; color: #64748b; line-height: 1.6; font-family: system-ui; }
.howto { padding: 100px 0; background: #000; }
.howto-title { color: #fff; }
.howto-sub { color: rgba(255,255,255,0.5); }
.steps-grid { display: grid; grid-template-columns: 1fr auto 1fr auto 1fr; gap: 20px; align-items: center; }
.step-card { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; padding: 32px 24px; text-align: center; }
.step-num { font-size: 13px; font-weight: 700; color: #DB854F; font-family: system-ui; margin-bottom: 14px; letter-spacing: 2px; }
.step-icon { font-size: 36px; margin-bottom: 14px; }
.step-card h3 { font-size: 17px; color: #fff; margin-bottom: 10px; }
.step-card p { font-size: 14px; color: rgba(255,255,255,0.5); line-height: 1.6; font-family: system-ui; }
.step-arrow { font-size: 28px; color: #DB854F; }
.roles { padding: 100px 0; background: #fff; }
.roles .section-title { color: #000; }
.roles .section-sub { color: #64748b; }
.roles-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; }
.role-card { border-radius: 20px; padding: 32px; border: 1px solid rgba(0,0,0,0.08); transition: all 0.3s; background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
.role-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(0,0,0,0.12); }
.role-member { border-top: 4px solid #B7D3F4; }
.role-trainer { border-top: 4px solid #365A82; }
.role-admin { border-top: 4px solid #DB854F; }
.role-icon { font-size: 36px; margin-bottom: 16px; }
.role-card h3 { font-size: 20px; color: #000; margin-bottom: 10px; }
.role-card > p { font-size: 14px; color: #64748b; line-height: 1.6; margin-bottom: 20px; font-family: system-ui; }
.role-card ul { list-style: none; display: flex; flex-direction: column; gap: 8px; margin-bottom: 24px; }
.role-card ul li { font-size: 13px; color: #334155; font-family: system-ui; }
.role-btn { display: inline-block; background: #0f172a; color: #fff; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-size: 13px; font-weight: 600; font-family: system-ui; transition: all 0.2s; }
.role-btn:hover { background: #DB854F; transform: translateY(-1px); }
.about { padding: 100px 0; background: #F4E8E3; }
.about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; }
.about-text .section-title { color: #000; }
.about-text > p { font-size: 15px; color: #64748b; line-height: 1.8; margin-bottom: 32px; font-family: system-ui; }
.about-points { display: flex; flex-direction: column; gap: 20px; }
.about-point { display: flex; gap: 14px; align-items: flex-start; }
.ap-icon { width: 40px; height: 40px; border-radius: 10px; background: rgba(219,133,79,0.1); display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
.about-point strong { display: block; font-size: 15px; margin-bottom: 4px; color: #000; }
.about-point p { font-size: 13px; color: #64748b; line-height: 1.5; font-family: system-ui; }
.about-visual { display: flex; align-items: center; justify-content: center; }
.about-card { background: #000; border-radius: 20px; padding: 28px; width: 100%; max-width: 340px; }
.ac-header { font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid rgba(255,255,255,0.1); }
.ac-items { display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px; }
.ac-item { display: flex; align-items: center; gap: 10px; font-size: 14px; color: rgba(255,255,255,0.7); font-family: system-ui; }
.ac-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.ac-dot.orange { background: #DB854F; }
.ac-dot.blue { background: #365A82; }
.ac-dot.dark { background: #475569; }
.ac-dot.lightblue { background: #B7D3F4; }
.ac-footer { display: flex; gap: 8px; flex-wrap: wrap; }
.ac-badge { background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.7); padding: 4px 10px; border-radius: 20px; font-size: 11px; font-family: system-ui; }
.cta { padding: 100px 24px; background: #365A82; text-align: center; }
.cta h2 { font-size: 36px; color: #fff; margin-bottom: 12px; }
.cta p { color: rgba(255,255,255,0.7); margin-bottom: 32px; font-family: system-ui; font-size: 16px; }
.btn-large { padding: 16px 36px; font-size: 17px; }
.footer { background: #000; padding: 60px 0 0; }
.footer-container { max-width: 1200px; margin: 0 auto; padding: 0 24px 40px; display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 40px; }
.footer-brand p { color: rgba(255,255,255,0.4); font-size: 13px; margin-top: 12px; line-height: 1.6; font-family: system-ui; }
.footer-address { margin-top: 6px; color: rgba(255,255,255,0.3); font-size: 12px; }
.footer-links { display: flex; flex-direction: column; gap: 10px; }
.footer-links h4 { color: #fff; font-size: 14px; margin-bottom: 4px; }
.footer-links a { color: rgba(255,255,255,0.4); text-decoration: none; font-size: 13px; font-family: system-ui; transition: color 0.2s; }
.footer-links a:hover { color: #DB854F; }
.footer-bottom { border-top: 1px solid rgba(255,255,255,0.08); padding: 20px 24px; text-align: center; }
.footer-bottom p { color: rgba(255,255,255,0.3); font-size: 12px; font-family: system-ui; }
@media (max-width: 768px) {
  .nav-links { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: #000; flex-direction: column; align-items: center; justify-content: center; gap: 28px; z-index: 999; }
  .nav-links.open { display: flex; }
  .nav-links a { font-size: 20px; }
  .hamburger { display: flex; z-index: 1000; }
  .hero { padding: 100px 20px 60px; }
  .hero-content { grid-template-columns: 1fr; gap: 40px; text-align: center; }
  .hero-sub { margin: 0 auto 32px; }
  .hero-btns { justify-content: center; }
  .hero-stats { justify-content: center; }
  .hero-visual { height: 260px; }
  .card-1 { top: 5%; left: 0; }
  .card-2 { top: 40%; right: 0; }
  .card-3 { bottom: 5%; left: 5%; }
  .steps-grid { grid-template-columns: 1fr; }
  .step-arrow { display: none; }
  .about-grid { grid-template-columns: 1fr; }
  .footer-container { grid-template-columns: 1fr; gap: 24px; }
  .cta h2 { font-size: 26px; }
}
@media (max-width: 480px) {
  .hero-title { font-size: 30px; }
  .hero-card { padding: 10px 14px; }
  .hc-title { font-size: 12px; }
  .hc-sub { font-size: 10px; }
}
</style>
