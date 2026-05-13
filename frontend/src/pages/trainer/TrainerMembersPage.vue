<script setup lang="ts">
import { onMounted } from 'vue'
import WorkspaceLayout from '../../components/layout/WorkspaceLayout.vue'
import { trainerSidebarItems } from '../../components/layout/sidebarItems'
import FitnezCard from '../../components/ui/FitnezCard.vue'
import StatCard from '../../components/ui/StatCard.vue'
import { useTrainerMemberMonitoringStore } from '../../stores/trainerMemberMonitoringStore'

const store = useTrainerMemberMonitoringStore()

function formatDate(value?: string | null) {
  if (!value) return '-'
  return new Date(value).toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
  })
}

function roleName(user: any) {
  return user?.role?.name || user?.role || 'member'
}

function statusClass(status?: string | null) {
  if (status === 'active' || status === 'completed') return 'status status-success'
  if (status === 'pending' || status === 'draft') return 'status status-warning'
  if (status === 'cancelled' || status === 'inactive') return 'status status-danger'
  return 'status status-info'
}

onMounted(() => {
  store.loadSummary()
  store.loadMembers()
})
</script>

<template>
  <WorkspaceLayout
    role="trainer"
    sidebar-title="Trainer"
    title="Member Fitness Monitoring"
    subtitle="Monitor workout plans, training tracking, nutrition targets, and member meal plans."
    :sidebar-items="trainerSidebarItems"
  >
    <div class="report-stat-grid">
      <StatCard label="Members" :value="store.summary?.total_members || 0" hint="Active member accounts" />
      <StatCard label="Active Plans" :value="store.summary?.active_workout_plans || 0" hint="Workout plans in progress" />
      <StatCard label="Completed Logs" :value="store.summary?.completed_trackings || 0" hint="Training tracking records" />
      <StatCard label="Meal Plans" :value="store.summary?.meal_plans || 0" hint="Nutrition planning records" />
    </div>

    <div class="monitor-layout">
      <FitnezCard>
        <div class="card-heading">
          <div>
            <p class="eyebrow">Member List</p>
            <h2 class="title-md">Choose member</h2>
            <p class="text-muted">Search a member, then open the fitness detail panel.</p>
          </div>
        </div>

        <label class="filter-field">
          <span>Search member</span>
          <input
            class="form-input"
            placeholder="Name or email..."
            @input="store.setSearch(($event.target as HTMLInputElement).value)"
          />
        </label>

        <div class="member-list">
          <button
            v-for="member in store.members"
            :key="member.id"
            type="button"
            :class="['member-card', { 'member-card-active': store.selected?.member.id === member.id }]"
            @click="store.loadDetail(member.id)"
          >
            <span class="member-avatar">{{ member.full_name?.charAt(0) || 'M' }}</span>
            <span class="member-copy">
              <strong>{{ member.full_name }}</strong>
              <small>{{ member.email }}</small>
              <span class="member-meta">
                <span>{{ member.workout_plans_count || 0 }} plans</span>
                <span>{{ member.workout_trackings_count || 0 }} logs</span>
                <span>{{ member.meal_plans_count || 0 }} meals</span>
              </span>
            </span>
          </button>

          <div v-if="!store.members.length && !store.loadingMembers" class="soft-empty">
            <p>No members found.</p>
            <span>Try another search keyword or add member data first.</span>
          </div>
        </div>

        <div class="pager-bar">
          <p>Page {{ store.page }} of {{ store.lastPage }}</p>
          <div>
            <button class="button button-ghost button-small" :disabled="store.page <= 1" @click="store.previousPage">Previous</button>
            <button class="button button-ghost button-small" :disabled="store.page >= store.lastPage" @click="store.nextPage">Next</button>
          </div>
        </div>
      </FitnezCard>

      <FitnezCard>
        <div v-if="store.loadingDetail" class="soft-empty soft-empty-large">
          <p>Loading member detail...</p>
          <span>Fetching workout, nutrition, and meal plan records.</span>
        </div>

        <div v-else-if="store.selected" class="detail-stack">
          <div class="detail-hero">
            <div class="member-avatar member-avatar-large">
              {{ store.selected.member.full_name?.charAt(0) || 'M' }}
            </div>
            <div>
              <p class="eyebrow">Selected Member</p>
              <h2 class="title-md">{{ store.selected.member.full_name }}</h2>
              <p class="text-muted">{{ store.selected.member.email }}</p>
            </div>
            <span class="status status-info">{{ roleName(store.selected.member) }}</span>
          </div>

          <div class="mini-stat-grid">
            <StatCard label="Plans" :value="store.selected.summary.workout_plans" hint="Total workout plans" />
            <StatCard label="Active" :value="store.selected.summary.active_workout_plans" hint="Currently active" />
            <StatCard label="Completed" :value="store.selected.summary.completed_trackings" hint="Finished workouts" />
            <StatCard label="Incomplete" :value="store.selected.summary.incomplete_trackings" hint="Unfinished tracking" />
          </div>

          <section class="report-section nutrition-section">
            <div class="section-heading">
              <div>
                <h3 class="title-md">Nutrition calculator</h3>
                <p class="text-muted">Latest calorie and macro target calculated for this member.</p>
              </div>
            </div>

            <div v-if="store.selected.nutrition" class="nutrition-grid">
              <div class="nutrition-tile">
                <span>BMR</span>
                <strong>{{ store.selected.nutrition.bmr || '-' }}</strong>
                <small>Basal metabolic rate</small>
              </div>
              <div class="nutrition-tile">
                <span>TDEE</span>
                <strong>{{ store.selected.nutrition.tdee || '-' }}</strong>
                <small>Daily energy estimate</small>
              </div>
              <div class="nutrition-tile highlight">
                <span>Calories</span>
                <strong>{{ store.selected.nutrition.target_calories || '-' }}</strong>
                <small>Target intake</small>
              </div>
              <div class="nutrition-tile">
                <span>Protein</span>
                <strong>{{ store.selected.nutrition.target_protein || '-' }}</strong>
                <small>Target grams</small>
              </div>
            </div>
            <p v-else class="soft-empty-inline">No nutrition calculation yet.</p>
          </section>

          <section class="report-section">
            <div class="section-heading">
              <div>
                <h3 class="title-md">Workout plans</h3>
                <p class="text-muted">Plan period, current status, and assigned exercises.</p>
              </div>
            </div>

            <div class="responsive-table">
              <table class="data-table compact-table">
                <thead>
                  <tr>
                    <th>Plan</th>
                    <th>Status</th>
                    <th>Period</th>
                    <th>Exercises</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="plan in store.selected.workout_plans" :key="plan.id">
                    <td>
                      <p class="strong-text">{{ plan.title }}</p>
                      <p class="text-muted small-text">{{ plan.description || 'No description' }}</p>
                    </td>
                    <td><span :class="statusClass(plan.status)">{{ plan.status || 'not set' }}</span></td>
                    <td>{{ formatDate(plan.start_date) }} - {{ formatDate(plan.end_date) }}</td>
                    <td>
                      <div v-for="item in plan.workout_exercises" :key="item.id" class="stack-line">
                        <strong>{{ item.exercise?.name || 'Exercise' }}</strong>
                        <span>Day {{ item.day_of_week }}, {{ item.sets }} × {{ item.reps }}</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="!store.selected.workout_plans.length">
                    <td colspan="4" class="empty-cell">No workout plans yet.</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="mobile-record-list">
              <article v-for="plan in store.selected.workout_plans" :key="`plan-${plan.id}`" class="mobile-record-card">
                <div class="mobile-record-head">
                  <strong>{{ plan.title }}</strong>
                  <span :class="statusClass(plan.status)">{{ plan.status || 'not set' }}</span>
                </div>
                <p>{{ plan.description || 'No description' }}</p>
                <small>{{ formatDate(plan.start_date) }} - {{ formatDate(plan.end_date) }}</small>
                <div class="mobile-chip-row">
                  <span v-for="item in plan.workout_exercises" :key="`exercise-${item.id}`">
                    {{ item.exercise?.name || 'Exercise' }} · {{ item.sets }} × {{ item.reps }}
                  </span>
                </div>
              </article>
              <div v-if="!store.selected.workout_plans.length" class="soft-empty">No workout plans yet.</div>
            </div>
          </section>

          <section class="report-section">
            <div class="section-heading">
              <div>
                <h3 class="title-md">Recent training tracking</h3>
                <p class="text-muted">Actual sets, reps, weight, and completion status.</p>
              </div>
            </div>

            <div class="responsive-table">
              <table class="data-table compact-table">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Exercise</th>
                    <th>Actual</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="tracking in store.selected.workout_trackings" :key="tracking.id">
                    <td>{{ formatDate(tracking.workout_date) }}</td>
                    <td>{{ tracking.workout_exercise?.exercise?.name || 'Exercise' }}</td>
                    <td>{{ tracking.actual_sets || 0 }} sets, {{ tracking.actual_reps || 0 }} reps, {{ tracking.actual_weight_kg || 0 }} kg</td>
                    <td>
                      <span :class="tracking.is_completed ? 'status status-success' : 'status status-warning'">
                        {{ tracking.is_completed ? 'completed' : 'not completed' }}
                      </span>
                    </td>
                  </tr>
                  <tr v-if="!store.selected.workout_trackings.length">
                    <td colspan="4" class="empty-cell">No training tracking yet.</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="mobile-record-list">
              <article v-for="tracking in store.selected.workout_trackings" :key="`tracking-${tracking.id}`" class="mobile-record-card">
                <div class="mobile-record-head">
                  <strong>{{ tracking.workout_exercise?.exercise?.name || 'Exercise' }}</strong>
                  <span :class="tracking.is_completed ? 'status status-success' : 'status status-warning'">
                    {{ tracking.is_completed ? 'completed' : 'not completed' }}
                  </span>
                </div>
                <p>{{ tracking.actual_sets || 0 }} sets · {{ tracking.actual_reps || 0 }} reps · {{ tracking.actual_weight_kg || 0 }} kg</p>
                <small>{{ formatDate(tracking.workout_date) }}</small>
              </article>
              <div v-if="!store.selected.workout_trackings.length" class="soft-empty">No training tracking yet.</div>
            </div>
          </section>

          <section class="report-section">
            <div class="section-heading">
              <div>
                <h3 class="title-md">Meal plans</h3>
                <p class="text-muted">Daily meal plan and macro composition.</p>
              </div>
            </div>

            <div class="responsive-table">
              <table class="data-table compact-table">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Plan</th>
                    <th>Macros</th>
                    <th>Meals</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="plan in store.selected.meal_plans" :key="plan.id">
                    <td>{{ formatDate(plan.plan_date) }}</td>
                    <td class="strong-text">{{ plan.title }}</td>
                    <td>{{ plan.total_calories || 0 }} kcal · P {{ plan.protein_grams || 0 }}g · C {{ plan.carbs_grams || 0 }}g · F {{ plan.fat_grams || 0 }}g</td>
                    <td>
                      <div v-for="meal in plan.meals" :key="meal.id" class="stack-line">
                        <strong>{{ meal.meal_type }}</strong>
                        <span>{{ meal.food_name }}</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="!store.selected.meal_plans.length">
                    <td colspan="4" class="empty-cell">No meal plans yet.</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="mobile-record-list">
              <article v-for="plan in store.selected.meal_plans" :key="`meal-plan-${plan.id}`" class="mobile-record-card">
                <div class="mobile-record-head">
                  <strong>{{ plan.title }}</strong>
                  <small>{{ formatDate(plan.plan_date) }}</small>
                </div>
                <p>{{ plan.total_calories || 0 }} kcal · P {{ plan.protein_grams || 0 }}g · C {{ plan.carbs_grams || 0 }}g · F {{ plan.fat_grams || 0 }}g</p>
                <div class="mobile-chip-row">
                  <span v-for="meal in plan.meals" :key="`meal-${meal.id}`">{{ meal.meal_type }} · {{ meal.food_name }}</span>
                </div>
              </article>
              <div v-if="!store.selected.meal_plans.length" class="soft-empty">No meal plans yet.</div>
            </div>
          </section>
        </div>

        <div v-else class="soft-empty soft-empty-large">
          <p>Choose a member</p>
          <span>Member fitness details will appear here after a member is selected.</span>
        </div>
      </FitnezCard>
    </div>
  </WorkspaceLayout>
</template>

<style scoped>
.report-stat-grid,
.mini-stat-grid,
.nutrition-grid {
  display: grid;
  gap: 1rem;
  grid-template-columns: repeat(auto-fit, minmax(min(100%, 13rem), 1fr));
}

.monitor-layout {
  display: grid;
  gap: 1.25rem;
  margin-top: 1.25rem;
  min-width: 0;
}

.monitor-layout > :deep(.card) {
  min-width: 0;
  overflow: hidden;
}

.monitor-layout,
.detail-stack,
.report-section,
.responsive-table {
  max-width: 100%;
}

.card-heading,
.section-heading,
.pager-bar,
.mobile-record-head {
  align-items: flex-start;
  display: flex;
  gap: 1rem;
  justify-content: space-between;
}

.detail-hero {
  align-items: center;
  display: grid;
  gap: 1rem;
  grid-template-columns: auto minmax(0, 1fr) auto;
}

.filter-field {
  display: grid;
  gap: 0.5rem;
  margin-top: 1rem;
}

.filter-field span {
  font-size: 0.85rem;
  font-weight: 900;
}

.member-list,
.detail-stack {
  display: grid;
  gap: 1rem;
  margin-top: 1rem;
  min-width: 0;
}

.member-card {
  align-items: flex-start;
  background: rgba(255, 255, 255, 0.92);
  border: 1px solid rgba(0, 0, 0, 0.12);
  border-radius: var(--radius-md);
  box-shadow: var(--shadow-panel);
  cursor: pointer;
  display: flex;
  gap: 0.85rem;
  padding: 1rem;
  text-align: left;
  transition: 160ms ease;
  width: 100%;
}

.member-card:hover,
.member-card-active {
  background: var(--color-soft-blue);
  border-color: var(--color-blue);
  transform: translateY(-1px);
}

.member-avatar {
  align-items: center;
  background: var(--color-blue-dark);
  border-radius: 1rem;
  color: var(--color-white);
  display: inline-flex;
  flex: 0 0 auto;
  font-weight: 900;
  height: 2.75rem;
  justify-content: center;
  text-transform: uppercase;
  width: 2.75rem;
}

.member-avatar-large {
  border-radius: var(--radius-md);
  font-size: 1.35rem;
  height: 4rem;
  width: 4rem;
}

.member-copy {
  display: grid;
  gap: 0.3rem;
  min-width: 0;
}

.member-copy strong,
.member-copy small {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.member-copy small,
.mobile-record-card small,
.stack-line span {
  color: var(--color-muted);
  font-weight: 700;
}

.member-meta,
.mobile-chip-row {
  display: flex;
  flex-wrap: wrap;
  gap: 0.45rem;
}

.member-meta span,
.mobile-chip-row span {
  background: rgba(255, 255, 255, 0.75);
  border: 1px solid rgba(0, 0, 0, 0.10);
  border-radius: 999px;
  font-size: 0.75rem;
  font-weight: 900;
  padding: 0.25rem 0.55rem;
}

.pager-bar {
  align-items: center;
  border-top: 1px solid rgba(0, 0, 0, 0.10);
  margin-top: 1rem;
  padding-top: 1rem;
}

.pager-bar p {
  color: var(--color-muted);
  font-size: 0.85rem;
  font-weight: 800;
}

.pager-bar div {
  display: flex;
  gap: 0.5rem;
}

.detail-hero {
  background: linear-gradient(135deg, rgba(183, 211, 244, 0.56), rgba(255, 255, 255, 0.92));
  border: 1px solid rgba(54, 90, 130, 0.20);
  border-radius: var(--radius-lg);
  padding: 1rem;
}

.detail-hero > div:not(.member-avatar) {
  min-width: 0;
}

.detail-hero .title-md,
.detail-hero .text-muted {
  overflow-wrap: anywhere;
}

.report-section {
  background: rgba(255, 255, 255, 0.78);
  border: 1px solid rgba(0, 0, 0, 0.10);
  border-radius: var(--radius-lg);
  min-width: 0;
  padding: 1rem;
}

.nutrition-grid {
  margin-top: 1rem;
}

.nutrition-tile {
  background: var(--color-white);
  border: 1px solid rgba(0, 0, 0, 0.10);
  border-radius: var(--radius-md);
  display: grid;
  gap: 0.35rem;
  padding: 1rem;
}

.nutrition-tile.highlight {
  background: var(--color-blue-dark);
  color: var(--color-white);
}

.nutrition-tile span,
.nutrition-tile small {
  color: inherit;
  font-size: 0.8rem;
  font-weight: 800;
  opacity: 0.76;
}

.nutrition-tile strong {
  font-size: clamp(1.45rem, 4vw, 2rem);
  font-weight: 900;
}

.responsive-table {
  margin-top: 1rem;
  max-width: 100%;
  overflow-x: auto;
  overscroll-behavior-inline: contain;
  -webkit-overflow-scrolling: touch;
}

.compact-table {
  min-width: 760px;
}

.compact-table th,
.compact-table td {
  white-space: normal;
}

.compact-table td:first-child {
  min-width: 12rem;
}

.strong-text {
  font-weight: 900;
}

.small-text {
  font-size: 0.8rem;
}

.stack-line {
  display: grid;
  gap: 0.1rem;
  margin-bottom: 0.4rem;
}

.empty-cell {
  color: var(--color-muted);
  font-weight: 800;
  padding-block: 2rem;
  text-align: center;
}

.soft-empty,
.soft-empty-inline {
  background: var(--color-cream);
  border: 1px dashed rgba(0, 0, 0, 0.18);
  border-radius: var(--radius-md);
  color: var(--color-muted);
  font-weight: 800;
  padding: 1rem;
}

.soft-empty p,
.soft-empty-large p {
  color: var(--color-black);
  font-weight: 900;
  margin-bottom: 0.25rem;
}

.soft-empty-large {
  padding: clamp(1.5rem, 6vw, 3rem);
  text-align: center;
}

.mobile-record-list {
  display: none;
}

.mobile-record-card {
  background: var(--color-white);
  border: 1px solid rgba(0, 0, 0, 0.10);
  border-radius: var(--radius-md);
  display: grid;
  gap: 0.75rem;
  padding: 1rem;
}

.mobile-record-card p {
  color: var(--color-muted);
  font-weight: 700;
  line-height: 1.6;
}

@media (min-width: 900px) and (max-width: 1499px) {
  .monitor-layout {
    grid-template-columns: 1fr;
  }

  .member-list {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

@media (min-width: 1500px) {
  .monitor-layout {
    align-items: start;
    grid-template-columns: minmax(17.5rem, 0.72fr) minmax(0, 1.72fr);
  }

  .member-list {
    max-height: calc(100vh - 25rem);
    min-height: 18rem;
    overflow-y: auto;
    padding-right: 0.25rem;
  }
}

@media (max-width: 760px) {
  .card-heading,
  .section-heading,
  .pager-bar,
  .mobile-record-head {
    align-items: stretch;
    flex-direction: column;
  }

  .detail-hero {
    grid-template-columns: 1fr;
    text-align: left;
  }

  .pager-bar div,
  .pager-bar button {
    width: 100%;
  }

  .responsive-table {
    display: none;
  }

  .mobile-record-list {
    display: grid;
    gap: 0.75rem;
    margin-top: 1rem;
  }
}
</style>
