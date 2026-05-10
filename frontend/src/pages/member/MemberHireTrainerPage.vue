<script setup lang="ts">
import WorkspaceLayout from '../../components/layout/WorkspaceLayout.vue'
import { memberSidebarItems } from '../../components/layout/sidebarItems'
import FitnezCard from '../../components/ui/FitnezCard.vue'
</script>

<template>
  <WorkspaceLayout
    role="member"
    sidebar-title="Member"
    title="Hire a Trainer"
    subtitle="Find trainer support for your fitness goal."
    :sidebar-items="memberSidebarItems"
  >
    <div class="feature-grid">
      <FitnezCard>
        <p class="eyebrow">Trainer</p>
        <h2 class="title-md">Personal Coaching</h2>
        <p class="text-muted">
          Request trainer guidance for technique, workout structure, and progress planning.
        </p>
      </FitnezCard>

      <FitnezCard>
        <p class="eyebrow">Schedule</p>
        <h2 class="title-md">Book a Session</h2>
        <p class="text-muted">
          Prepare a booking flow for available trainer sessions.
        </p>
      </FitnezCard>

      <FitnezCard>
        <p class="eyebrow">Goal</p>
        <h2 class="title-md">Custom Fitness Goal</h2>
        <p class="text-muted">
          Match trainer support with cutting, bulking, strength, or general health goals.
        </p>
      </FitnezCard>
    </div>
  </WorkspaceLayout>
</template>
