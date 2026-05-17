<template>
  <div class="page">
    <div class="page-header">
      <div>
        <h1>Bookings</h1>
        <p>Manage trainer session bookings</p>
      </div>
    </div>

    <div v-if="user.role_id == 3" class="form-card">
      <h2>Create Booking</h2>

      <div class="package-select">
        <div class="package-card" :class="{ active: form.package === 'gym' }" @click="form.package = 'gym'">
          <div class="pkg-icon">🏋️</div>
          <div class="pkg-name">Gym Only</div>
          <div class="pkg-desc">Access gym equipment and facilities without a personal trainer.</div>
          <div class="pkg-price">Rp 250,000 / session</div>
        </div>
        <div class="package-card" :class="{ active: form.package === 'gym+trainer' }" @click="form.package = 'gym+trainer'">
          <div class="pkg-icon">💪</div>
          <div class="pkg-name">Gym + Trainer</div>
          <div class="pkg-desc">Get a personal trainer to guide your workout session.</div>
          <div class="pkg-price">Rp 1,200,000 / session</div>
        </div>
      </div>

      <div class="form-grid" style="margin-top:20px">
        <div class="form-group">
          <label>Booking Date *</label>
          <input v-model="form.booking_date" type="date" />
        </div>
        <div class="form-group">
          <label>Start Time *</label>
          <input v-model="form.start_time" type="time" />
        </div>
        <div class="form-group">
          <label>End Time *</label>
          <input v-model="form.end_time" type="time" />
        </div>
        <div class="form-group">
          <label>Location</label>
          <input v-model="form.location" placeholder="e.g. Main Gym" />
        </div>
      </div>

      <div v-if="form.package === 'gym+trainer'" class="trainer-section">
        <h3>Trainer Details</h3>
        <div class="form-grid">
          <div class="form-group">
            <label>Trainer ID *</label>
            <input v-model="form.trainer_id" placeholder="Enter trainer ID" />
          </div>
          <div class="form-group">
            <label>Session Type</label>
            <select v-model="form.session_type">
              <option value="">Select type</option>
              <option value="strength_training">Strength Training</option>
              <option value="cardio">Cardio</option>
              <option value="yoga">Yoga</option>
              <option value="pilates">Pilates</option>
              <option value="crossfit">CrossFit</option>
            </select>
          </div>
        </div>
      </div>

      <div class="form-group" style="margin-top:12px">
        <label>Notes</label>
        <textarea v-model="form.member_notes" placeholder="Any special requests or notes..." rows="3"></textarea>
      </div>

      <div class="booking-summary">
        <span>Package: <strong>{{ form.package === 'gym' ? 'Gym Only' : 'Gym + Trainer' }}</strong></span>
        <span>Total: <strong>Rp {{ form.package === 'gym' ? '250.000' : '1.200.000' }}</strong></span>
      </div>

      <button @click="createBooking" class="btn-book">Book Now</button>
    </div>

    <div class="table-card">
      <div class="table-top">
        <h3>Booking Records</h3>
        <span class="record-count">{{ bookings.length }} records</span>
      </div>
      <table v-if="bookings.length">
        <thead>
          <tr>
            <th>ID</th>
            <th>Member ID</th>
            <th>Trainer ID</th>
            <th>Date</th>
            <th>Session Type</th>
            <th>Status</th>
            <th>Total Price</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="b in bookings" :key="b.id">
            <td>#{{ b.id }}</td>
            <td>{{ b.member_id }}</td>
            <td>{{ b.trainer_id || 'Gym Only' }}</td>
            <td>{{ b.booking_date }}</td>
            <td>{{ b.session_type || 'gym' }}</td>
            <td>
              <span class="badge" :class="b.status === 'confirmed' ? 'badge-confirmed' : 'badge-pending'">
                {{ b.status || 'pending' }}
              </span>
            </td>
            <td>Rp {{ Number(b.total_price || 0).toLocaleString('id-ID') }}</td>
          </tr>
        </tbody>
      </table>
      <div v-else class="empty-state">No bookings found.</div>
    </div>
  </div>
</template>

<script>
import { apiGet, apiPost } from '../services/api'
export default {
  data() {
    return {
      user: JSON.parse(localStorage.getItem('user') || '{}'),
      bookings: [],
      form: {
        package: 'gym',
        trainer_id: '',
        booking_date: '',
        start_time: '',
        end_time: '',
        session_type: 'gym',
        location: '',
        member_notes: ''
      }
    }
  },
  methods: {
    async loadBookings() {
      try {
        let res
        if (this.user.role_id == 1) {
          res = await apiGet('bookings/all')
        } else if (this.user.role_id == 2) {
          res = await apiGet(`bookings/trainer/${this.user.id}`)
        } else {
          res = await apiGet(`bookings/user/${this.user.id}`)
        }
        this.bookings = res.data || []
      } catch (error) {
        console.error('LOAD BOOKINGS ERROR:', error)
        this.bookings = []
      }
    },
    async createBooking() {
      if (!this.form.booking_date || !this.form.start_time || !this.form.end_time) {
        alert('Please fill in the required fields: Date, Start Time, End Time')
        return
      }
      if (this.form.package === 'gym+trainer' && !this.form.trainer_id) {
        alert('Please enter a Trainer ID for Gym + Trainer package')
        return
      }
      try {
        const totalPrice = this.form.package === 'gym' ? 250000 : 1200000
        const res = await apiPost('bookings', {
          member_id: this.user.id,
          trainer_id: this.form.package === 'gym+trainer' ? this.form.trainer_id : null,
          booking_date: this.form.booking_date,
          start_time: this.form.start_time,
          end_time: this.form.end_time,
          session_type: this.form.package === 'gym+trainer' ? this.form.session_type : 'gym',
          location: this.form.location || 'Main Gym',
          member_note: this.form.member_notes,
          total_price: totalPrice
        })
        alert(res.message || 'Booking created successfully!')
        this.loadBookings()
      } catch (error) {
        console.error('CREATE BOOKING ERROR:', error)
        alert(error.message || 'Booking failed')
      }
    }
  },
  mounted() {
    this.loadBookings()
  }
}
</script>

<style scoped>
.page { padding: 30px; }
.page-header { margin-bottom: 24px; }
.page-header h1 { margin: 0; font-size: 28px; color: #000; }
.page-header p { margin-top: 4px; color: #64748b; font-size: 14px; }
.form-card { background: white; border-radius: 20px; padding: 28px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border-top: 3px solid #DB854F; }
.form-card h2 { margin: 0 0 20px; color: #000; font-size: 20px; }
.package-select { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.package-card { border: 2px solid #e2e8f0; border-radius: 16px; padding: 20px; cursor: pointer; transition: all 0.2s; }
.package-card:hover { border-color: #DB854F; }
.package-card.active { border-color: #DB854F; background: rgba(219,133,79,0.05); }
.pkg-icon { font-size: 32px; margin-bottom: 10px; }
.pkg-name { font-size: 16px; font-weight: 700; color: #000; margin-bottom: 6px; }
.pkg-desc { font-size: 13px; color: #64748b; line-height: 1.5; margin-bottom: 10px; font-family: system-ui; }
.pkg-price { font-size: 15px; font-weight: 700; color: #DB854F; }
.trainer-section { background: #F4E8E3; border-radius: 14px; padding: 18px; margin-top: 16px; }
.trainer-section h3 { margin: 0 0 14px; font-size: 15px; color: #000; }
.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; }
.form-group { display: flex; flex-direction: column; gap: 5px; }
.form-group label { font-size: 12px; font-weight: 500; color: #64748b; }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px 12px; border-radius: 10px; border: 1px solid #e2e8f0; outline: none; font-size: 14px; font-family: inherit; transition: 0.2s; box-sizing: border-box; }
.form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: #DB854F; }
.booking-summary { display: flex; justify-content: space-between; align-items: center; background: #F4E8E3; border-radius: 12px; padding: 14px 18px; margin-top: 16px; font-size: 14px; font-family: system-ui; color: #334155; }
.booking-summary strong { color: #DB854F; }
.btn-book { margin-top: 16px; padding: 12px 28px; border: none; border-radius: 12px; background: #DB854F; color: white; font-weight: 600; font-size: 15px; cursor: pointer; transition: 0.2s; }
.btn-book:hover { background: #c4723e; transform: translateY(-1px); }
.table-card { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
.table-top { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid #e2e8f0; }
.table-top h3 { margin: 0; font-size: 15px; color: #000; }
.record-count { font-size: 12px; color: #64748b; background: #F4E8E3; padding: 4px 10px; border-radius: 20px; }
table { width: 100%; border-collapse: collapse; }
th { background: #000; color: white; text-align: left; padding: 12px 14px; font-size: 13px; }
td { padding: 12px 14px; border-bottom: 1px solid #e2e8f0; color: #334155; font-size: 13px; }
tr:hover td { background: #F4E8E3; }
.badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 500; }
.badge-confirmed { background: #dcfce7; color: #166534; }
.badge-pending { background: rgba(219,133,79,0.15); color: #92400e; }
.empty-state { text-align: center; padding: 40px; color: #64748b; }
</style>