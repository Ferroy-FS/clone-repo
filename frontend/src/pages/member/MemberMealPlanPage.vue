<script setup lang="ts">
import { computed, ref } from 'vue'
import WorkspaceLayout from '../../components/layout/WorkspaceLayout.vue'
import { memberSidebarItems } from '../../components/layout/sidebarItems'
import FitnezCard from '../../components/ui/FitnezCard.vue'
import FitnezInput from '../../components/ui/FitnezInput.vue'

const protein = ref(120)
const carbs = ref(250)
const fat = ref(60)

const calories = computed(() => {
  return protein.value * 4 + carbs.value * 4 + fat.value * 9
})
</script>

<template>
  <WorkspaceLayout
    role="member"
    sidebar-title="Member"
    title="Meal Plan"
    subtitle="Food nutrition calculator for protein, carbs, and fat."
    :sidebar-items="memberSidebarItems"
  >
    <div class="feature-grid">
      <FitnezCard>
        <p class="eyebrow">Calculator</p>
        <h2 class="title-md">Food Nutrition Calculator</h2>
        <p class="text-muted">
          Estimate total calories from protein, carbohydrates, and fat.
        </p>
      </FitnezCard>

      <FitnezCard>
        <p class="eyebrow">Meal Plan</p>
        <h2 class="title-md">Daily Meal Planning</h2>
        <p class="text-muted">
          Prepare meal targets based on your training goal.
        </p>
      </FitnezCard>

      <FitnezCard>
        <p class="eyebrow">Macro</p>
        <h2 class="title-md">Protein, Carbs, Fat</h2>
        <p class="text-muted">
          Balance your macronutrients for cutting, bulking, or maintenance.
        </p>
      </FitnezCard>
    </div>

    <FitnezCard style="margin-top: 1.25rem;">
      <h2 class="title-md">Nutrition Calculator</h2>

      <div style="display: grid; gap: 1rem; margin-top: 1rem;">
        <FitnezInput v-model.number="protein" label="Protein (grams)" type="number" min="0" />
        <FitnezInput v-model.number="carbs" label="Carbs (grams)" type="number" min="0" />
        <FitnezInput v-model.number="fat" label="Fat (grams)" type="number" min="0" />
      </div>

      <div class="panel panel-orange" style="margin-top: 1rem;">
        <p class="eyebrow-accent">Estimated Calories</p>
        <p class="stat-value">{{ calories }} kcal</p>
        <p class="text-muted">
          Formula: protein × 4 + carbs × 4 + fat × 9.
        </p>
      </div>
    </FitnezCard>
  </WorkspaceLayout>
</template>
