<template>
  <div class="page">

    <div class="page-header">
      <div>
        <h1>Members</h1>
      <p> feel happy with our fitness programs </p>
      </div>
      <button v-if="user.role_id == 1" class="btn-add" @click="showModal = true">
        + Add Member
      </button>
    </div>
    <div class="table-card">
      <table v-if="members.length">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Height</th>
            <th>Weight</th>
            <th>Fitness Goal</th>
            <th>Experience</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="m in members" :key="m.id">
            <td>{{ m.id }}</td>
            <td>{{ m.full_name || m.name || '-' }}</td>
            <td>{{ m.email || '-' }}</td>
            <td>{{ m.phone || '-' }}</td>
            <td>{{ m.height_cm || m.heightCm || '-' }} cm</td>
            <td>{{ m.weight_kg || m.weightKg || '-' }} kg</td>
            <td>{{ m.fitness_goals || m.fitnessGoals || '-' }}</td>
            <td>{{ m.experience_level || m.experienceLevel || '-' }}</td>
          </tr>
        </tbody>
      </table>
      <div v-else class="empty-state">No member data found.</div>
    </div>

    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal">
        <div class="modal-header">
          <h2>Add New Member</h2>
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

          <p class="section-label">Fitness Profile</p>
          <div class="form-grid">
            <div class="form-group">
              <label>Height (cm)</label>
              <input v-model="form.height_cm" type="number" placeholder="e.g. 170" />
            </div>
            <div class="form-group">
              <label>Weight (kg)</label>
              <input v-model="form.weight_kg" type="number" placeholder="e.g. 65" />
            </div>
            <div class="form-group">
              <label>Fitness Goal</label>
              <select v-model="form.fitness_goals">
                <option value="">Select goal</option>
                <option value="weight_loss">Weight Loss</option>
                <option value="muscle_gain">Muscle Gain</option>
                <option value="endurance">Endurance</option>
                <option value="flexibility">Flexibility</option>
                <option value="general_fitness">General Fitness</option>
              </select>
            </div>
            <div class="form-group">
              <label>Experience Level</label>
              <select v-model="form.experience_level">
                <option value="">Select level</option>
                <option value="beginner">Beginner</option>
                <option value="intermediate">Intermediate</option>
                <option value="advanced">Advanced</option>
              </select>
            </div>
          </div>

          <p v-if="error" class="error-msg">{{ error }}</p>
        </div>

        <div class="modal-footer">
          <button class="btn-cancel" @click="closeModal">Cancel</button>
          <button class="btn-submit" :disabled="loading" @click="addMember">
            {{ loading ? 'Adding...' : 'Add Member' }}
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
      members: [],
      showModal: false,
      loading: false,
      error: '',
      form: {
        full_name: '',
        email: '',
        password: '',
        phone: '',
        birth_date: '',
        height_cm: '',
        weight_kg: '',
        fitness_goals: '',
        experience_level: ''
      }
    }
  },

  methods: {
    async loadMembers() {
      try {
        let res
        if (this.user.role_id == 1) {
          res = await apiGet('members/all')
          this.members = res.members || []
        } else if (this.user.role_id == 2) {
          res = await apiGet(`members/trainer/${this.user.id}`)
          this.members = res.members || []
        } else {
          res = await apiGet(`members/${this.user.id}`)
          this.members = res.member ? [res.member] : []
        }
      } catch (error) {
        console.error('LOAD MEMBERS ERROR:', error)
        this.members = []
      }
    },

    async addMember() {
      // Validate required fields
      if (!this.form.full_name || !this.form.email ||
          !this.form.password || !this.form.phone ||
          !this.form.birth_date) {
        this.error = 'Please fill all required fields (*)'
        return
      }

      this.loading = true
      this.error = ''

      try {
        // Step 1 — Register user account
        const registerRes = await apiPost('auth/register', {
          full_name: this.form.full_name,
          email: this.form.email,
          password: this.form.password,
          phone: this.form.phone,
          birth_date: this.form.birth_date,
          role_id: 3 // Member role
        })

        if (!registerRes.status) {
          this.error = registerRes.message || 'Failed to create account'
          return
        }

        const newUserId = registerRes.user.id

        // Step 2 — Create member profile
        if (this.form.height_cm || this.form.weight_kg ||
            this.form.fitness_goals || this.form.experience_level) {
          await apiPost('members/create', {
            user_id: newUserId,
            height_cm: this.form.height_cm || null,
            weight_kg: this.form.weight_kg || null,
            fitness_goals: this.form.fitness_goals || null,
            experience_level: this.form.experience_level || null
          })
        }

        alert(`Member "${this.form.full_name}" added successfully!`)
        this.closeModal()
        this.loadMembers()

      } catch (err) {
        console.error('ADD MEMBER ERROR:', err)
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
        phone: '', birth_date: '', height_cm: '',
        weight_kg: '', fitness_goals: '', experience_level: ''
      }
    }
  },

  mounted() {
    this.loadMembers()
  }
}
</script>

<style scoped>
.page { padding: 30px; }

/* HEADER */
.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}
.page-header h1 { margin-bottom: 4px; color: #0f172a; }
.page-header p { color: #64748b; }

/* ADD BUTTON */
.btn-add {
  background: #DB854F;
  color: white;
  border: none;
  padding: 12px 22px;
  border-radius: 14px;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  transition: 0.2s;
  white-space: nowrap;
}
.btn-add:hover { background: #c4723e; transform: translateY(-1px); }

/* TABLE */
.table-card {
  background: white;
  border-radius: 22px;
  padding: 22px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.06);
  overflow-x: auto;
}
table { width: 100%; border-collapse: collapse; }
th, td { padding: 14px; border-bottom: 1px solid #e2e8f0; text-align: left; }
th { color: #475569; font-size: 13px; text-transform: uppercase; }
tr:hover td { background: #f8fafc; }
.empty-state {
  text-align: center; padding: 40px;
  color: #94a3b8; font-weight: 600;
}

/* MODAL OVERLAY */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999;
  padding: 20px;
}

/* MODAL BOX */
.modal {
  background: white;
  border-radius: 24px;
  width: 100%;
  max-width: 620px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 20px 60px rgba(0,0,0,0.2);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 22px 24px 16px;
  border-bottom: 1px solid #e2e8f0;
}
.modal-header h2 { margin: 0; color: #0f172a; font-size: 20px; }
.btn-close {
  background: #f1f5f9; border: none;
  width: 32px; height: 32px;
  border-radius: 50%; cursor: pointer;
  font-size: 14px; color: #64748b;
}

.modal-body { padding: 20px 24px; }

.section-label {
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  color: #365A82;
  letter-spacing: 0.06em;
  margin: 16px 0 10px;
}
.section-label:first-child { margin-top: 0; }

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 12px;
}

.form-group { display: flex; flex-direction: column; gap: 5px; }
.form-group label { font-size: 12px; font-weight: 500; color: #475569; }
.form-group input,
.form-group select {
  padding: 10px 12px;
  border: 1px solid #cbd5e1;
  border-radius: 10px;
  font-size: 14px;
  outline: none;
  transition: 0.2s;
}
.form-group input:focus,
.form-group select:focus { border-color: #DB854F; }

.error-msg {
  margin-top: 12px;
  color: #ef4444;
  font-size: 13px;
  font-weight: 500;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  padding: 16px 24px 22px;
  border-top: 1px solid #e2e8f0;
}

.btn-cancel {
  background: #f1f5f9; color: #64748b;
  border: none; padding: 10px 20px;
  border-radius: 12px; cursor: pointer;
  font-weight: 500;
}
.btn-submit {
  background: #DB854F; color: white;
  border: none; padding: 10px 24px;
  border-radius: 12px; cursor: pointer;
  font-weight: 600;
  transition: 0.2s;
}
.btn-submit:hover:not(:disabled) { background: #c4723e; }
.btn-submit:disabled { opacity: 0.6; cursor: not-allowed; }
</style>
