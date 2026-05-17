<template>
  <div class="page">
    <div class="page-header">
      <div>
        <h1>Trainers</h1>
        <p>Gym trainers with wonderful expertise</p>
      </div>
      <button v-if="user.role_id == 1" class="btn-add" @click="showModal = true">
        + Add Trainer
      </button>
    </div>

    <div class="table-card">
      <table v-if="trainers.length">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Specialization</th>
            <th>Experience</th>
            <th>Hourly Rate</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="t in trainers" :key="t.id">
            <td>{{ t.id }}</td>
            <td>{{ t.user?.full_name || '-' }}</td>
            <td>{{ t.user?.email || '-' }}</td>
            <td>{{ t.specialization || '-' }}</td>
            <td>{{ t.experience_years || 0 }} years</td>
            <td>Rp {{ t.hourly_rate || 0 }}</td>
          </tr>
        </tbody>
      </table>
      <div v-else class="empty-state">No trainer data found.</div>
    </div>

    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal">
        <div class="modal-header">
          <h2>Add New Trainer</h2>
          <button class="btn-close" @click="closeModal">✕</button>
        </div>
        <div class="modal-body">
          <p class="section-label">Account Info</p>
          <div class="form-grid">
            <div class="form-group">
              <label>Full Name *</label>
              <input v-model="form.full_name" placeholder="Enter full name" />
            </div>
            <div class="form-group">
              <label>Email *</label>
              <input v-model="form.email" type="email" placeholder="Enter email" />
            </div>
            <div class="form-group">
              <label>Password *</label>
              <input v-model="form.password" type="password" placeholder="Min 4 characters" />
            </div>
            <div class="form-group">
              <label>Phone *</label>
              <input v-model="form.phone" placeholder="Enter phone number" />
            </div>
            <div class="form-group">
              <label>Birth Date *</label>
              <input v-model="form.birth_date" type="date" />
            </div>
          </div>
          <p class="section-label">Trainer Profile</p>
          <div class="form-grid">
            <div class="form-group">
              <label>Specialization</label>
              <select v-model="form.specialization">
                <option value="">Select specialization</option>
                <option value="strength_training">Strength Training</option>
                <option value="cardio">Cardio</option>
                <option value="yoga">Yoga</option>
                <option value="pilates">Pilates</option>
                <option value="crossfit">CrossFit</option>
                <option value="nutrition">Nutrition</option>
                <option value="martial_arts">Martial Arts</option>
              </select>
            </div>
            <div class="form-group">
              <label>Experience (years)</label>
              <input v-model="form.experience_years" type="number" placeholder="e.g. 3" min="0" />
            </div>
            <div class="form-group">
              <label>Hourly Rate (Rp)</label>
              <input v-model="form.hourly_rate" type="number" placeholder="e.g. 150000" min="0" />
            </div>
            <div class="form-group">
              <label>Certification</label>
              <input v-model="form.certification" placeholder="e.g. ACE, NASM" />
            </div>
          </div>
          <div class="form-group" style="margin-top:12px;">
            <label>Bio</label>
            <textarea v-model="form.bio" placeholder="Short trainer bio..." rows="3"></textarea>
          </div>
          <p v-if="error" class="error-msg">{{ error }}</p>
        </div>
        <div class="modal-footer">
          <button class="btn-cancel" @click="closeModal">Cancel</button>
          <button class="btn-submit" :disabled="loading" @click="addTrainer">
            {{ loading ? 'Adding...' : 'Add Trainer' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { apiGet, apiPost } from '../services/api'
export default {
  data() {
    return {
      user: JSON.parse(localStorage.getItem('user') || '{}'),
      trainers: [],
      showModal: false,
      loading: false,
      error: '',
      form: {
        full_name: '', email: '', password: '',
        phone: '', birth_date: '', specialization: '',
        experience_years: '', hourly_rate: '',
        certification: '', bio: ''
      }
    }
  },
  methods: {
    async loadTrainers() {
      try {
        let res
        if (this.user.role_id == 2) {
          res = await apiGet(`trainers/${this.user.id}`)
          this.trainers = res.data ? [res.data] : []
        } else {
          res = await apiGet('trainers')
          this.trainers = res.data || []
        }
      } catch (error) {
        console.error('LOAD TRAINERS ERROR:', error)
        this.trainers = []
      }
    },
    async addTrainer() {
      if (!this.form.full_name || !this.form.email || !this.form.password || !this.form.phone || !this.form.birth_date) {
        this.error = 'Please fill all required fields (*)'
        return
      }
      this.loading = true
      this.error = ''
      try {
        const registerRes = await apiPost('auth/register', {
          full_name: this.form.full_name,
          email: this.form.email,
          password: this.form.password,
          phone: this.form.phone,
          birth_date: this.form.birth_date,
          role_id: 2
        })
        if (!registerRes.status) {
          this.error = registerRes.message || 'Failed to create account'
          return
        }
        const newUserId = registerRes.user.id
        await apiPost('trainers/create', {
          user_id: newUserId,
          specialization: this.form.specialization || null,
          experience_years: this.form.experience_years || 0,
          hourly_rate: this.form.hourly_rate || 0,
          certification: this.form.certification || null,
          bio: this.form.bio || null
        })
        alert(`Trainer "${this.form.full_name}" added successfully!`)
        this.closeModal()
        this.loadTrainers()
      } catch (err) {
        console.error('ADD TRAINER ERROR:', err)
        this.error = err.message || 'Server error. Please try again.'
      } finally {
        this.loading = false
      }
    },
    closeModal() {
      this.showModal = false
      this.error = ''
      this.form = {
        full_name: '', email: '', password: '',
        phone: '', birth_date: '', specialization: '',
        experience_years: '', hourly_rate: '',
        certification: '', bio: ''
      }
    }
  },
  mounted() {
    this.loadTrainers()
  }
}
</script>

<style scoped>
.page { padding: 30px; }
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
.page-header h1 { margin-bottom: 4px; color: #000; font-size: 28px; }
.page-header p { color: #64748b; font-size: 14px; }
.btn-add { background: #DB854F; color: white; border: none; padding: 12px 22px; border-radius: 14px; font-weight: 600; font-size: 14px; cursor: pointer; transition: 0.2s; white-space: nowrap; }
.btn-add:hover { background: #c4723e; transform: translateY(-1px); }
.table-card { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
table { width: 100%; border-collapse: collapse; }
th { background: #000; color: white; padding: 12px 14px; text-align: left; font-size: 13px; }
td { padding: 12px 14px; border-bottom: 1px solid #e2e8f0; color: #334155; font-size: 13px; }
tr:hover td { background: #F4E8E3; }
.empty-state { text-align: center; padding: 40px; color: #64748b; }
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 999; padding: 20px; }
.modal { background: white; border-radius: 24px; width: 100%; max-width: 620px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.2); }
.modal-header { display: flex; justify-content: space-between; align-items: center; padding: 22px 24px 16px; border-bottom: 1px solid #e2e8f0; }
.modal-header h2 { margin: 0; color: #000; font-size: 20px; }
.btn-close { background: #F4E8E3; border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; font-size: 14px; color: #64748b; }
.modal-body { padding: 20px 24px; }
.section-label { font-size: 12px; font-weight: 600; text-transform: uppercase; color: #DB854F; letter-spacing: 0.06em; margin: 16px 0 10px; }
.section-label:first-child { margin-top: 0; }
.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; }
.form-group { display: flex; flex-direction: column; gap: 5px; }
.form-group label { font-size: 12px; font-weight: 500; color: #64748b; }
.form-group input, .form-group select, .form-group textarea { padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 14px; outline: none; transition: 0.2s; font-family: inherit; }
.form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: #DB854F; }
.error-msg { margin-top: 12px; color: #ef4444; font-size: 13px; font-weight: 500; }
.modal-footer { display: flex; justify-content: flex-end; gap: 12px; padding: 16px 24px 22px; border-top: 1px solid #e2e8f0; }
.btn-cancel { background: #F4E8E3; color: #64748b; border: none; padding: 10px 20px; border-radius: 12px; cursor: pointer; font-weight: 500; }
.btn-submit { background: #DB854F; color: white; border: none; padding: 10px 24px; border-radius: 12px; cursor: pointer; font-weight: 600; transition: 0.2s; }
.btn-submit:hover:not(:disabled) { background: #c4723e; }
.btn-submit:disabled { opacity: 0.6; cursor: not-allowed; }
</style>