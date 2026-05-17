<script setup lang="ts">
import RoleLayout from '../../components/layout/RoleLayout.vue'
import { memberSidebarItems } from '../../components/layout/sidebarItems'
import FitnezCard from '../../components/ui/FitnezCard.vue'

</script>

<template>
  <RoleLayout role="member" sidebar-title="Member" title="Schedule" subtitle="View your class and trainer schedule." :sidebar-items="memberSidebarItems">

    <FitnezCard class="">
      <h2 class="text-2xl font-black">Schedule Module</h2>
      <p class="mt-3 max-w-3xl text-sm font-semibold leading-6 text-black/55">
        This page is designed for member users. Backend integration can be connected when the module function is finalized.
      </p>
    </FitnezCard>
  </RoleLayout>
</template>
