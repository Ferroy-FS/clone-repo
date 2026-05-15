<script setup lang="ts">
import { ref } from 'vue'
import WorkspaceSidebar from './WorkspaceSidebar.vue'

type MenuItem = {
  label: string
  to?: string
  icon?: string
  submenu?: Array<{ label: string; to: string; icon?: string }>
}

defineProps<{
  role: 'admin' | 'member' | 'trainer'
  title: string
  subtitle?: string
  sidebarTitle: string
  sidebarItems: MenuItem[]
}>()

const mobileOpen = ref(false)
</script>

<template>
  <div class="page workspace-layout">
    <aside class="sidebar-shell">
      <WorkspaceSidebar :role="role" :title="sidebarTitle" :items="sidebarItems" @close="mobileOpen = false" />
    </aside>

    <div v-if="mobileOpen" class="mobile-drawer">
      <button class="mobile-overlay" type="button" aria-label="Close menu" @click="mobileOpen = false" />
      <aside class="mobile-sidebar">
        <WorkspaceSidebar :role="role" :title="sidebarTitle" :items="sidebarItems" @close="mobileOpen = false" />
      </aside>
    </div>

    <main class="workspace-main">
      <div class="workspace-container">
        <header class="workspace-header">
          <button type="button" class="menu-button" @click="mobileOpen = true">☰</button>

          <div>
            <p class="eyebrow">{{ role }}</p>
            <h1 class="title-lg">{{ title }}</h1>
            <p v-if="subtitle" class="text-muted">{{ subtitle }}</p>
          </div>

          <div class="panel">
            <p class="stat-label">Workspace</p>
            <p class="title-md">{{ role }}</p>
          </div>
        </header>

        <slot />
      </div>
    </main>
  </div>
</template>
