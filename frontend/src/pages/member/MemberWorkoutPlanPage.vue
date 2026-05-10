<script setup lang="ts">
import WorkspaceLayout from '../../components/layout/WorkspaceLayout.vue'
import { memberSidebarItems } from '../../components/layout/sidebarItems'
import FitnezCard from '../../components/ui/FitnezCard.vue'

const features = [
  {
    title: 'Training Schedule',
    text: 'Plan weekly workout sessions and keep your training routine organized.',
  },
  {
    title: 'Exercise Tracking',
    text: 'Track completed exercises, sets, reps, and progress over time.',
  },
  {
    title: 'Video Tutorials',
    text: 'Learn correct exercise technique through guided training videos.',
  },
]
</script>

<template>
  <WorkspaceLayout
    role="member"
    sidebar-title="Member"
    title="Workout Plan"
    subtitle="Training schedule, exercise tracking, and video tutorials."
    :sidebar-items="memberSidebarItems"
  >
    <div class="feature-grid">
      <FitnezCard v-for="item in features" :key="item.title">
        <p class="eyebrow">Workout</p>
        <h2 class="title-md">{{ item.title }}</h2>
        <p class="text-muted">{{ item.text }}</p>
      </FitnezCard>
    </div>

    <FitnezCard style="margin-top: 1.25rem;">
      <h2 class="title-md">Workout Plan Overview</h2>
      <p class="text-muted">
        This menu is prepared for member workout management. Backend integration can later store schedules, exercise logs, and tutorial content.
      </p>
    </FitnezCard>
  </WorkspaceLayout>
</template>
