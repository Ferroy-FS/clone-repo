<script setup lang="ts">
import { ref } from 'vue'
import { RouterLink, useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/authStore'

type MenuItem = {
  label: string
  to?: string
  icon?: string
  submenu?: Array<{ label: string; to: string; icon?: string }>
}

defineProps<{
  role: 'admin' | 'member' | 'trainer'
  title: string
  items: MenuItem[]
}>()

const emit = defineEmits<{ close: [] }>()
const route = useRoute()
const router = useRouter()
const auth = useAuthStore()
const openSubmenu = ref<string | null>(null)

function close() {
  emit('close')
}

function toggleSubmenu(label: string) {
  openSubmenu.value = openSubmenu.value === label ? null : label
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
      <template v-for="item in items" :key="item.label">
        <!-- Menu dengan submenu -->
        <div v-if="item.submenu">
          <button
            type="button"
            :class="['sidebar-link', openSubmenu === item.label && 'sidebar-link-active']"
            style="width: 100%; text-align: left; display: flex; justify-content: space-between; align-items: center;"
            @click="toggleSubmenu(item.label)"
          >
            <span style="display: flex; align-items: center; gap: 0.75rem;">
              <span>{{ item.icon || '•' }}</span>
              <span>{{ item.label }}</span>
            </span>
            <span style="transition: transform 0.2s;" :style="{ transform: openSubmenu === item.label ? 'rotate(180deg)' : 'rotate(0deg)' }">▼</span>
          </button>
          <div v-if="openSubmenu === item.label" style="padding-left: 1rem; margin-top: 0.25rem;">
            <RouterLink
              v-for="sub in item.submenu"
              :key="sub.to"
              :to="sub.to"
              :class="['sidebar-link', route.path === sub.to && 'sidebar-link-active']"
              style="font-size: 0.9rem;"
              @click="close"
            >
              <span>{{ sub.icon || '•' }}</span>
              <span>{{ sub.label }}</span>
            </RouterLink>
          </div>
        </div>

        <!-- Menu biasa tanpa submenu -->
        <RouterLink
          v-else
          :to="item.to!"
          :class="['sidebar-link', route.path === item.to && 'sidebar-link-active']"
          @click="close"
        >
          <span>{{ item.icon || '•' }}</span>
          <span>{{ item.label }}</span>
        </RouterLink>
      </template>
    </nav>

    <div class="sidebar-footer">
      <button type="button" class="sidebar-link" style="width: 100%; text-align: left;" @click="logout">
        Logout / Switch Account
      </button>
    </div>
  </div>
</template>
