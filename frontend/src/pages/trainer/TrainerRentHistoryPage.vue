<script setup lang="ts">
import { onMounted } from 'vue'
import WorkspaceLayout from '../../components/layout/WorkspaceLayout.vue'
import { trainerSidebarItems } from '../../components/layout/sidebarItems'
import FitnezCard from '../../components/ui/FitnezCard.vue'
import StatCard from '../../components/ui/StatCard.vue'
import { useTrainerRentHistoryStore } from '../../stores/trainerRentHistoryStore'

const store = useTrainerRentHistoryStore()

function currency(value?: string | number | null) {
  const numberValue = Number(value || 0)
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(numberValue)
}

function formatDate(value?: string | null) {
  if (!value) return '-'
  return new Date(value).toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
  })
}

function statusClass(status?: string | null) {
  if (status === 'disbursed' || status === 'paid') return 'status status-success'
  if (status === 'pending') return 'status status-warning'
  if (status === 'failed' || status === 'rejected') return 'status status-danger'
  return 'status status-info'
}

onMounted(() => {
  store.loadSummary()
  store.load()
})
</script>

<template>
  <WorkspaceLayout
    role="trainer"
    sidebar-title="Trainer"
    title="Incoming Rent History"
    subtitle="Track trainer income from paid sessions, commission, and disbursement status."
    :sidebar-items="trainerSidebarItems"
  >
    <div class="report-stat-grid">
      <StatCard label="Records" :value="store.summary?.total_records || 0" hint="Income rows" />
      <StatCard label="Total Income" :value="currency(store.summary?.total_trainer_amount || 0)" hint="Trainer amount" />
      <StatCard label="Pending" :value="currency(store.summary?.pending_amount || 0)" hint="Waiting disbursement" />
      <StatCard label="Disbursed" :value="currency(store.summary?.disbursed_amount || 0)" hint="Already paid out" />
    </div>

    <FitnezCard class="report-card">
      <div class="report-toolbar">
        <div>
          <p class="eyebrow">Income Report</p>
          <h2 class="title-md">Rent history records</h2>
          <p class="text-muted">Filter by invoice, member, payment method, date, or disbursement status.</p>
        </div>
        <button class="button button-primary toolbar-button" @click="store.refresh">Apply Filter</button>
      </div>

      <div class="filter-grid">
        <label class="filter-field">
          <span>Search</span>
          <input v-model="store.search" class="form-input" placeholder="Invoice, member, method..." />
        </label>
        <label class="filter-field">
          <span>Status</span>
          <select v-model="store.status" class="form-input">
            <option value="">All status</option>
            <option value="pending">Pending</option>
            <option value="disbursed">Disbursed</option>
            <option value="failed">Failed</option>
          </select>
        </label>
        <label class="filter-field">
          <span>Start date</span>
          <input v-model="store.startDate" class="form-input" type="date" />
        </label>
        <label class="filter-field">
          <span>End date</span>
          <input v-model="store.endDate" class="form-input" type="date" />
        </label>
      </div>

      <div class="responsive-table">
        <table class="data-table report-table">
          <thead>
            <tr>
              <th>Member</th>
              <th>Session</th>
              <th>Invoice</th>
              <th>Payment</th>
              <th>Commission</th>
              <th>Trainer Amount</th>
              <th>Status</th>
              <th>Disbursed</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="row in store.items" :key="row.id">
              <td>
                <p class="strong-text">{{ row.booking?.member?.full_name || row.payment?.user?.full_name || '-' }}</p>
                <p class="text-muted small-text">{{ row.booking?.member?.email || row.payment?.user?.email || '-' }}</p>
              </td>
              <td>
                <p class="strong-text">{{ row.booking?.session_type || '-' }}</p>
                <p class="text-muted small-text">{{ formatDate(row.booking?.booking_date) }} · {{ row.booking?.start_time || '-' }}</p>
              </td>
              <td>{{ row.payment?.invoice_number || '-' }}</td>
              <td>
                <p class="strong-text">{{ currency(row.payment?.amount || 0) }}</p>
                <p class="text-muted small-text">{{ row.payment?.payment_method || '-' }} · {{ row.payment?.payment_status || '-' }}</p>
              </td>
              <td>{{ row.commission_rate }}%</td>
              <td class="strong-text">{{ currency(row.trainer_amount) }}</td>
              <td><span :class="statusClass(row.status)">{{ row.status || 'not set' }}</span></td>
              <td>{{ formatDate(row.disbursed_at) }}</td>
            </tr>
            <tr v-if="!store.items.length && !store.loading">
              <td colspan="8" class="empty-cell">No incoming rent history yet.</td>
            </tr>
            <tr v-if="store.loading">
              <td colspan="8" class="empty-cell">Loading incoming rent history...</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="mobile-record-list">
        <article v-for="row in store.items" :key="`rent-${row.id}`" class="mobile-record-card">
          <div class="mobile-record-head">
            <div>
              <strong>{{ row.booking?.member?.full_name || row.payment?.user?.full_name || '-' }}</strong>
              <small>{{ row.payment?.invoice_number || 'No invoice' }}</small>
            </div>
            <span :class="statusClass(row.status)">{{ row.status || 'not set' }}</span>
          </div>

          <div class="mobile-amount">
            <span>Trainer amount</span>
            <strong>{{ currency(row.trainer_amount) }}</strong>
          </div>

          <dl class="mobile-detail-grid">
            <div>
              <dt>Session</dt>
              <dd>{{ row.booking?.session_type || '-' }}</dd>
            </div>
            <div>
              <dt>Schedule</dt>
              <dd>{{ formatDate(row.booking?.booking_date) }} · {{ row.booking?.start_time || '-' }}</dd>
            </div>
            <div>
              <dt>Payment</dt>
              <dd>{{ currency(row.payment?.amount || 0) }}</dd>
            </div>
            <div>
              <dt>Commission</dt>
              <dd>{{ row.commission_rate }}%</dd>
            </div>
            <div>
              <dt>Disbursed</dt>
              <dd>{{ formatDate(row.disbursed_at) }}</dd>
            </div>
          </dl>
        </article>
        <div v-if="!store.items.length && !store.loading" class="soft-empty">No incoming rent history yet.</div>
        <div v-if="store.loading" class="soft-empty">Loading incoming rent history...</div>
      </div>

      <div class="pager-bar">
        <p>{{ store.total }} records · Page {{ store.page }} of {{ store.lastPage }}</p>
        <div>
          <button class="button button-ghost button-small" :disabled="store.page <= 1" @click="store.previousPage">Previous</button>
          <button class="button button-ghost button-small" :disabled="store.page >= store.lastPage" @click="store.nextPage">Next</button>
        </div>
      </div>
    </FitnezCard>
  </WorkspaceLayout>
</template>

<style scoped>
.report-stat-grid {
  display: grid;
  gap: 1rem;
  grid-template-columns: repeat(auto-fit, minmax(min(100%, 13rem), 1fr));
}

.report-card {
  margin-top: 1.25rem;
}

.report-toolbar,
.pager-bar,
.mobile-record-head {
  align-items: flex-start;
  display: flex;
  gap: 1rem;
  justify-content: space-between;
}

.toolbar-button {
  flex: 0 0 auto;
}

.filter-grid {
  display: grid;
  gap: 0.85rem;
  grid-template-columns: repeat(auto-fit, minmax(min(100%, 12rem), 1fr));
  margin-top: 1.25rem;
}

.filter-field {
  display: grid;
  gap: 0.5rem;
}

.filter-field span {
  font-size: 0.85rem;
  font-weight: 900;
}

.responsive-table {
  margin-top: 1.25rem;
  overflow-x: auto;
}

.report-table {
  min-width: 1060px;
}

.strong-text {
  font-weight: 900;
}

.small-text {
  font-size: 0.8rem;
}

.empty-cell {
  color: var(--color-muted);
  font-weight: 800;
  padding-block: 2.5rem;
  text-align: center;
}

.pager-bar {
  align-items: center;
  border-top: 1px solid rgba(0, 0, 0, 0.10);
  margin-top: 1rem;
  padding-top: 1rem;
}

.pager-bar p {
  color: var(--color-muted);
  font-size: 0.875rem;
  font-weight: 800;
}

.pager-bar div {
  display: flex;
  gap: 0.5rem;
}

.mobile-record-list {
  display: none;
}

.mobile-record-card {
  background: var(--color-white);
  border: 1px solid rgba(0, 0, 0, 0.10);
  border-radius: var(--radius-md);
  box-shadow: var(--shadow-panel);
  display: grid;
  gap: 1rem;
  padding: 1rem;
}

.mobile-record-head strong,
.mobile-amount strong {
  display: block;
  font-weight: 900;
}

.mobile-record-head small,
.mobile-amount span,
.mobile-detail-grid dt {
  color: var(--color-muted);
  display: block;
  font-size: 0.78rem;
  font-weight: 800;
  margin-top: 0.2rem;
}

.mobile-amount {
  background: var(--color-blue-dark);
  border-radius: var(--radius-md);
  color: var(--color-white);
  padding: 1rem;
}

.mobile-amount span {
  color: rgba(255, 255, 255, 0.72);
  margin-top: 0;
}

.mobile-amount strong {
  font-size: 1.35rem;
  margin-top: 0.25rem;
}

.mobile-detail-grid {
  display: grid;
  gap: 0.75rem;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  margin: 0;
}

.mobile-detail-grid div {
  background: var(--color-cream);
  border-radius: 1rem;
  padding: 0.75rem;
}

.mobile-detail-grid dd {
  font-weight: 900;
  margin: 0.2rem 0 0;
}

.soft-empty {
  background: var(--color-cream);
  border: 1px dashed rgba(0, 0, 0, 0.18);
  border-radius: var(--radius-md);
  color: var(--color-muted);
  font-weight: 800;
  padding: 1rem;
  text-align: center;
}

@media (max-width: 760px) {
  .report-toolbar,
  .pager-bar,
  .mobile-record-head {
    align-items: stretch;
    flex-direction: column;
  }

  .toolbar-button,
  .pager-bar button,
  .pager-bar div {
    width: 100%;
  }

  .responsive-table {
    display: none;
  }

  .mobile-record-list {
    display: grid;
    gap: 0.85rem;
    margin-top: 1rem;
  }

  .mobile-detail-grid {
    grid-template-columns: 1fr;
  }
}
</style>
