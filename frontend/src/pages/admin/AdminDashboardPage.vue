<script setup lang="ts">
import WorkspaceLayout from '../../components/layout/WorkspaceLayout.vue'
import { adminSidebarItems } from '../../components/layout/sidebarItems'
import StatCard from '../../components/ui/StatCard.vue'
import FitnezCard from '../../components/ui/FitnezCard.vue'
</script>

<template>
  <WorkspaceLayout role="admin" sidebar-title="Admin" title="Dashboard" subtitle="Focused workspace for payment approvals and visitor tracking." :sidebar-items="adminSidebarItems">
    <div class="feature-grid">
      <StatCard label="Payment Review" value="Manual" hint="Approve uploaded proof" />
      <StatCard label="Landing Tracking" value="Active" hint="Visitors and browsers" />
      <StatCard label="Login Security" value="Direct" hint="Email + password login" />
    </div>

    <div style="display: grid; gap: 1.25rem; grid-template-columns: repeat(auto-fit, minmax(min(100%, 360px), 1fr)); margin-top: 1.25rem;">
      <FitnezCard>
        <h2 class="title-md">Admin tasks</h2>
        <div style="display: grid; gap: 0.75rem; margin-top: 1rem;">
          <div class="panel" style="background: var(--color-cream);">
            <p class="title-md">Review prospective member payments</p>
            <p class="text-muted">Open proof screenshots, confirm amount, then approve or reject.</p>
          </div>
          <div class="panel" style="background: var(--color-cream);">
            <p class="title-md">Monitor landing page visitors</p>
            <p class="text-muted">Track active users, browsers, devices, IP addresses, and private-mode signals.</p>
          </div>
        </div>
      </FitnezCard>

      <FitnezCard>
        <h2 class="title-md">Manual payment rule</h2>
        <p class="text-muted">
          A member account is created only after admin approval. This keeps inactive or unpaid accounts out of the system.
        </p>
      </FitnezCard>
    </div>
  </WorkspaceLayout>
</template>
