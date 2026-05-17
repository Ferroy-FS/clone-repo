<template>
  <div class="page">
    <div class="page-header">
      <div>
        <h1>Payments</h1>
        <p>Manage and track all gym payment records</p>
      </div>
    </div>

    <div class="table-card">
      <table v-if="payments.length">
        <thead>
          <tr>
            <th>ID</th>
            <th>Invoice</th>
            <th>User ID</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Method</th>
            <th>Date</th>
            <th v-if="user.role_id == 3">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="p in payments" :key="p.id">
            <td>#{{ p.id }}</td>
            <td>{{ p.invoice_number }}</td>
            <td>{{ p.user_id }}</td>
            <td>Rp {{ formatCurrency(p.amount) }}</td>
            <td>
              <span class="badge" :class="p.payment_status === 'paid' ? 'badge-paid' : 'badge-pending'">
                {{ p.payment_status || 'pending' }}
              </span>
            </td>
            <td>{{ p.payment_method || '-' }}</td>
            <td>{{ formatDate(p.payment_date) }}</td>
            <td v-if="user.role_id == 3 && p.payment_status !== 'paid'">
              <div class="action-buttons">
                <button @click="payCash(p.id)" class="btn btn-cash">Cash</button>
                <button @click="payTransfer(p.id)" class="btn btn-transfer">Transfer</button>
                <button @click="payQris(p.id)" class="btn btn-qris">QRIS</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-else class="empty-state">No payments found.</div>
    </div>
  </div>
</template>

<script>
import { apiGet, apiPost } from '../services/api'
export default {
  data() {
    return {
      user: JSON.parse(localStorage.getItem('user') || '{}'),
      payments: []
    }
  },
  methods: {
    async loadPayments() {
      try {
        let res
        if (this.user.role_id == 1) {
          res = await apiGet('payments/all')
        } else {
          res = await apiGet(`payments/user/${this.user.id}`)
        }
        this.payments = res.data || []
      } catch (error) {
        console.error('Payment load error:', error)
        this.payments = []
      }
    },
    async payCash(id) {
      const res = await apiPost(`payments/cash/${id}`, {})
      alert(res.message || 'Cash payment successful')
      this.loadPayments()
    },
    async payTransfer(id) {
      const res = await apiPost(`payments/transfer/${id}`, {
        bank_name: 'BCA',
        bank_account_number: '1234567890',
        bank_account_name: 'Gym Member',
        transfer_proof_url: 'proof.jpg'
      })
      alert(res.message || 'Transfer payment successful')
      this.loadPayments()
    },
    async payQris(id) {
      const res = await apiPost(`payments/qris/${id}`, {})
      alert(res.message || 'QRIS payment successful')
      this.loadPayments()
    },
    formatCurrency(value) {
      return Number(value || 0).toLocaleString('id-ID')
    },
    formatDate(date) {
      if (!date) return '-'
      return new Date(date).toLocaleString()
    }
  },
  mounted() {
    this.loadPayments()
  }
}
</script>

<style scoped>
.page { padding: 30px; }
.page-header { margin-bottom: 24px; }
.page-header h1 { margin: 0; font-size: 28px; color: #000; }
.page-header p { margin-top: 6px; color: #64748b; font-size: 14px; }
.table-card { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
table { width: 100%; border-collapse: collapse; }
th { background: #000; color: white; text-align: left; padding: 12px 14px; font-size: 13px; }
td { padding: 12px 14px; border-bottom: 1px solid #e2e8f0; color: #334155; font-size: 13px; }
tr:hover td { background: #F4E8E3; }
.badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.badge-paid { background: #dcfce7; color: #166534; }
.badge-pending { background: rgba(219,133,79,0.15); color: #92400e; }
.action-buttons { display: flex; gap: 8px; flex-wrap: wrap; }
.btn { border: none; padding: 7px 12px; border-radius: 8px; color: white; cursor: pointer; font-weight: 600; font-size: 12px; transition: 0.2s; }
.btn-cash { background: #DB854F; }
.btn-cash:hover { background: #c4723e; }
.btn-transfer { background: #365A82; }
.btn-transfer:hover { background: #2a4666; }
.btn-qris { background: #000; }
.btn-qris:hover { background: #333; }
.empty-state { text-align: center; padding: 40px; color: #64748b; }
</style>