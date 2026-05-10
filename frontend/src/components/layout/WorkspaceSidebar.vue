<script setup lang="ts">
import { RouterLink, useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/authStore'

defineProps<{
  role: 'admin' | 'member' | 'trainer'
  title: string
  items: Array<{ label: string; to: string; icon?: string }>
}>()

const emit = defineEmits<{ close: [] }>()
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
          <div>
            <p class="eyebrow eyebrow-light">Fitnez</p>
            <h1 class="sidebar-title">{{ title }}</h1>
          </div>
        </div>
        <p class="sidebar-subtitle">{{ role }} workspace</p>
      </div>
    </div>

    <nav class="sidebar-nav">
      <RouterLink
        v-for="item in items"
        :key="item.to"
        :to="item.to"
        :class="['sidebar-link', route.path === item.to && 'sidebar-link-active']"
        @click="close"
      >
        <span>{{ item.icon || '•' }}</span>
        <span>{{ item.label }}</span>
      </RouterLink>
    </nav>

    <div class="sidebar-footer">
      <button type="button" class="sidebar-link" style="width: 100%; text-align: left;" @click="logout">
        Logout / Switch Account
      </button>
    </div>
  </div>
</template>
