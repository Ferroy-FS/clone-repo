<template>
  <div class="page">
    <div class="page-header">
      <div>
        <h1>Trainer Earnings</h1>
        <p>Track mentoring income from trainer booking sessions</p>
      </div>
    </div>

    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-num">Rp {{ formatCurrency(totalEarnings) }}</div>
        <div class="stat-label">Total Earnings</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-num">Rp {{ formatCurrency(paidEarnings) }}</div>
        <div class="stat-label">Paid Earnings</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">⏳</div>
        <div class="stat-num">Rp {{ formatCurrency(pendingEarnings) }}</div>
        <div class="stat-label">Pending Earnings</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon">📋</div>
        <div class="stat-num">{{ earnings.length }}</div>
        <div class="stat-label">Total Sessions</div>
      </div>
    </div>

    <div class="table-card">
      <div class="table-top">
        <h3>Earnings History</h3>
        <span class="record-count">{{ earnings.length }} records</span>
      </div>
      <div class="table-wrap">

        <!-- ADMIN VIEW -->
        <table v-if="earnings.length && user.role_id == 1">
          <thead>
            <tr>
              <th>Trainer ID</th>
              <th>Trainer Name</th>
              <th>Total Earnings</th>
              <th>Total Sessions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="e in earnings" :key="e.trainer_id">
              <td>#{{ e.trainer_id }}</td>
              <td>{{ e.trainer_name }}</td>
              <td><strong>Rp {{ formatCurrency(e.total_earnings) }}</strong></td>
              <td>{{ e.total_sessions }}</td>
            </tr>
          </tbody>
        </table>

        <!-- TRAINER VIEW -->
        <table v-else-if="earnings.length && user.role_id == 2">
          <thead>
            <tr>
              <th>ID</th>
              <th>Booking ID</th>
              <th>Payment ID</th>
              <th>Commission Rate</th>
              <th>Trainer Amount</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="e in earnings" :key="e.id">
              <td>#{{ e.id }}</td>
              <td>{{ e.booking_id || '-' }}</td>
              <td>{{ e.payment_id || '-' }}</td>
              <td>{{ e.commission_rate }}%</td>
              <td><strong>Rp {{ formatCurrency(e.trainer_amount) }}</strong></td>
              <td>
                <span class="badge" :class="e.status === 'paid' ? 'badge-paid' : 'badge-pending'">
                  {{ e.status || 'pending' }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-else class="empty-state">No earnings records found.</div>
      </div>
    </div>
  </div>
</template>

<script>
import { apiGet } from '../services/api'
export default {
  data() {
    return {
      user: JSON.parse(localStorage.getItem('user') || '{}'),
      earnings: []
    }
  },
  computed: {
    totalEarnings() {
      if (this.user.role_id == 1) {
        return this.earnings.reduce((s, e) => s + Number(e.total_earnings || 0), 0)
      }
      return this.earnings.reduce((s, e) => s + Number(e.trainer_amount || 0), 0)
    },
    paidEarnings() {
      if (this.user.role_id == 1) return this.totalEarnings
      return this.earnings.filter(e => e.status === 'paid').reduce((s, e) => s + Number(e.trainer_amount || 0), 0)
    },
    pendingEarnings() {
      if (this.user.role_id == 1) return 0
      return this.earnings.filter(e => e.status !== 'paid').reduce((s, e) => s + Number(e.trainer_amount || 0), 0)
    }
  },
  methods: {
    async loadEarnings() {
      try {
        let res
        if (this.user.role_id == 1) {
          res = await apiGet('trainer-earnings/all')
        } else if (this.user.role_id == 2) {
          res = await apiGet('trainer-earnings/my')
        } else {
          this.earnings = []
          return
        }
        this.earnings = res.data || []
      } catch (error) {
        console.error('Earnings load error:', error)
        this.earnings = []
      }
    },
    formatCurrency(value) {
      return Number(value || 0).toLocaleString('id-ID')
    }
  },
  mounted() {
    this.loadEarnings()
  }
}
</script>

<style scoped>
.page { padding: 30px; }
.page-header { margin-bottom: 24px; }
.page-header h1 { margin: 0; font-size: 28px; color: #000; }
.page-header p { margin-top: 4px; color: #64748b; font-size: 14px; }
.stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 14px; margin-bottom: 24px; }
.stat-card { background: white; border-radius: 16px; padding: 18px; box-shadow: 0 4px 16px rgba(0,0,0,0.06); border-top: 3px solid #DB854F; }
.stat-icon { font-size: 20px; margin-bottom: 6px; }
.stat-num { font-size: 18px; font-weight: 600; color: #000; }
.stat-label { font-size: 12px; color: #64748b; margin-top: 3px; }
.table-card { background: white; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); overflow: hidden; }
.table-top { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid #e2e8f0; }
.table-top h3 { margin: 0; font-size: 15px; color: #000; }
.record-count { font-size: 12px; color: #64748b; background: #F4E8E3; padding: 4px 10px; border-radius: 20px; }
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; }
th { background: #000; color: white; text-align: left; padding: 12px 14px; font-size: 13px; }
td { padding: 12px 14px; border-bottom: 1px solid #e2e8f0; color: #334155; font-size: 13px; }
tr:hover td { background: #F4E8E3; }
.badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 500; }
.badge-paid { background: #dcfce7; color: #166534; }
.badge-pending { background: rgba(219,133,79,0.15); color: #92400e; }
.empty-state { text-align: center; padding: 40px; color: #64748b; }
</style>
