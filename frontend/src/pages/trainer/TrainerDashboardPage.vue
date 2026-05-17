<script setup lang="ts">
import WorkspaceLayout from '../../components/layout/WorkspaceLayout.vue'
import { trainerSidebarItems } from '../../components/layout/sidebarItems'
import StatCard from '../../components/ui/StatCard.vue'
import FitnezCard from '../../components/ui/FitnezCard.vue'
</script>

<template>
  <WorkspaceLayout role="trainer" sidebar-title="Trainer" title="Trainer Dashboard" subtitle="Trainer workspace for approved members." :sidebar-items="trainerSidebarItems">
    <div class="feature-grid">
      <StatCard label="Role" value="Trainer" hint="Access prepared" />
      <StatCard label="Schedule" value="Future" hint="Feature not active yet" />
      <StatCard label="Classes" value="Future" hint="Feature not active yet" />
    </div>

    <FitnezCard style="margin-top: 1.25rem;">
      <h2 class="title-md">Trainer workspace ready</h2>
      <p class="text-muted">
        This workspace is available after the member submits CV and certificate PDFs and admin approves the application. There is no separate trainer login form.
      </p>
    </FitnezCard>
  </WorkspaceLayout>
</template>
