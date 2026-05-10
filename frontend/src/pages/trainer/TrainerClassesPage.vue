<script setup lang="ts">
import RoleLayout from '../../components/layout/RoleLayout.vue'
import { trainerSidebarItems } from '../../components/layout/sidebarItems'
import FitnezCard from '../../components/ui/FitnezCard.vue'
import StatCard from '../../components/ui/StatCard.vue'
</script>

<template>
  <RoleLayout role="trainer" sidebar-title="Trainer" title="Classes" subtitle="Prepare and manage assigned class programs." :sidebar-items="trainerSidebarItems">
    <div class="grid gap-4 md:grid-cols-3">
      <StatCard label="Today Sessions" value="4" hint="Placeholder" />
      <StatCard label="Assigned Members" value="18" hint="Placeholder" />
      <StatCard label="Class Rating" value="4.8" hint="Placeholder" />
    </div>

    <FitnezCard class="mt-5">
      <h2 class="text-2xl font-black">Classes Module</h2>
      <p class="mt-3 max-w-3xl text-sm font-semibold leading-6 text-black/55">
        This trainer page is prepared for UI/UX. Connect backend functions later.
      </p>
    </FitnezCard>
  </RoleLayout>
</template>
