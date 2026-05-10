<script setup lang="ts">
import WorkspaceLayout from '../../components/layout/WorkspaceLayout.vue'
import { memberSidebarItems } from '../../components/layout/sidebarItems'
import StatCard from '../../components/ui/StatCard.vue'
import FitnezCard from '../../components/ui/FitnezCard.vue'
</script>

<template>
  <WorkspaceLayout role="member" sidebar-title="Member" title="Member Dashboard" subtitle="Membership overview after account activation." :sidebar-items="memberSidebarItems">
    <div class="feature-grid">
      <StatCard label="Account" value="Active" hint="After admin approval" />
      <StatCard label="Package" value="Membership" hint="Connected from registration" />
      <StatCard label="Class Access" value="Based on Package" hint="6M/12M include classes" />
      <StatCard label="Login" value="Direct" hint="OTP only for forgot password" />
    </div>

    <FitnezCard style="margin-top: 1.25rem;">
      <h2 class="title-md">Welcome to Fitnez</h2>
      <p class="text-muted">
        This dashboard focuses on the current working features: account status, package membership, and free-class eligibility.
      </p>
    </FitnezCard>
  </WorkspaceLayout>
</template>
