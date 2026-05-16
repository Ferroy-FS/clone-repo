<script setup>
import { RouterLink, useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/authStore'

defineProps({
  role: String,
  title: String,
  items: Array,
  collapsed: Boolean
})

const emit = defineEmits(['close'])
const route = useRoute()
const router = useRouter()
const auth = useAuthStore()

function close() {
  emit('close')
}

async function logout() {
  await auth.logout()
  close()
  await router.push('/login/member')
}
</script>

<template>
  <div class="sidebar">
    <div class="sidebar-header">
      <div class="sidebar-brand">
        <div class="sidebar-logo">
          <div class="sidebar-mark">F</div>
          <div v-if="!collapsed">
            <p class="eyebrow eyebrow-light">Fitnez</p>
            <h1 class="sidebar-title">{{ title }}</h1>
          </div>
        </div>
        <p v-if="!collapsed" class="sidebar-subtitle">{{ role }} workspace</p>
      </div>
    </div>

    <nav class="sidebar-nav">
      <RouterLink
        v-for="item in items"
        :key="item.to"
        :to="item.to"
        :class="['sidebar-link', route.path === item.to && 'sidebar-link-active', collapsed && 'sidebar-link-collapsed']"
        @click="close"
        :title="collapsed ? item.label : ''"
      >
        <span class="sidebar-icon">{{ item.icon || '•' }}</span>
        <span v-if="!collapsed">{{ item.label }}</span>
      </RouterLink>
    </nav>

    <div class="sidebar-footer">
      <button type="button" :class="['sidebar-link', collapsed && 'sidebar-link-collapsed']" style="width: 100%; text-align: left;" @click="logout" :title="collapsed ? 'Logout' : ''">
        <span class="sidebar-icon">🚪</span>
        <span v-if="!collapsed">Logout</span>
      </button>
    </div>
  </div>
</template>
