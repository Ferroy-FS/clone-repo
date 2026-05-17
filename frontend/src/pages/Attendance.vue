<template>
  <div class="page">
    <div class="page-header">
      <div>
        <h1>Attendance</h1>
        <p>Track gym check-in and check-out records</p>
      </div>
      <div v-if="user.role_id !== 1" class="actions">
        <button @click="checkIn" class="btn-checkin">Check In</button>
        <button @click="checkOut" class="btn-checkout">Check Out</button>
      </div>
    </div>

    <div class="table-card">
      <div class="table-top">
        <h3>Attendance Records</h3>
        <span class="record-count">{{ attendance.length }} records</span>
      </div>
      <table v-if="attendance.length">
        <thead>
          <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Check In</th>
            <th>Check Out</th>
            <th>Type</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="a in attendance" :key="a.id">
            <td>#{{ a.id }}</td>
            <td>{{ a.user_id }}</td>
            <td>{{ formatDate(a.check_in_time) }}</td>
            <td>{{ formatDate(a.check_out_time) }}</td>
            <td>
              <span class="badge">{{ a.attendance_type || 'gym' }}</span>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-else class="empty-state">No attendance data found.</div>
    </div>
  </div>
</template>

<script>
import { apiGet, apiPost } from '../services/api'
export default {
  data() {
    return {
      attendance: [],
      user: JSON.parse(localStorage.getItem('user') || '{}')
    }
  },
  methods: {
    async loadAttendance() {
      try {
        let res
        if (this.user.role_id == 1) {
          res = await apiGet('attendance/all')
        } else {
          res = await apiGet(`attendance/user/${this.user.id}`)
        }
        this.attendance = res.data || []
      } catch (error) {
        console.error('Attendance load error:', error)
        this.attendance = []
      }
    },
    async checkIn() {
      try {
        const res = await apiPost('attendance/checkin', { user_id: this.user.id })
        alert(res.message || 'Check-in successful')
        this.loadAttendance()
      } catch (error) {
        alert('Check-in failed')
      }
    },
    async checkOut() {
      try {
        const res = await apiPost('attendance/checkout', { user_id: this.user.id })
        alert(res.message || 'Check-out successful')
        this.loadAttendance()
      } catch (error) {
        alert('Check-out failed')
      }
    },
    formatDate(date) {
      if (!date) return '-'
      return new Date(date).toLocaleString()
    }
  },
  mounted() {
    this.loadAttendance()
  }
}
</script>

<style scoped>
.page { padding: 30px; }
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
.page-header h1 { margin: 0; font-size: 28px; color: #000; }
.page-header p { margin-top: 4px; color: #64748b; font-size: 14px; }
.actions { display: flex; gap: 12px; }
.btn-checkin { padding: 10px 20px; border: none; border-radius: 12px; background: #DB854F; color: white; cursor: pointer; font-weight: 600; font-size: 14px; transition: 0.2s; }
.btn-checkin:hover { background: #c4723e; }
.btn-checkout { padding: 10px 20px; border: none; border-radius: 12px; background: #000; color: white; cursor: pointer; font-weight: 600; font-size: 14px; transition: 0.2s; }
.btn-checkout:hover { background: #333; }
.table-card { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
.table-top { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid #e2e8f0; }
.table-top h3 { margin: 0; font-size: 15px; color: #000; }
.record-count { font-size: 12px; color: #64748b; background: #F4E8E3; padding: 4px 10px; border-radius: 20px; }
table { width: 100%; border-collapse: collapse; }
th { background: #000; color: white; text-align: left; padding: 12px 14px; font-size: 13px; }
td { padding: 12px 14px; border-bottom: 1px solid #e2e8f0; color: #334155; font-size: 13px; }
tr:hover td { background: #F4E8E3; }
.badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 500; background: rgba(219,133,79,0.15); color: #92400e; }
.empty-state { text-align: center; padding: 40px; color: #64748b; }
</style>