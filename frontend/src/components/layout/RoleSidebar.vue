<script setup lang="ts">
import { RouterLink, useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/authStore'

defineProps<{
  role: 'admin' | 'member' | 'trainer'
  title: string
  items: Array<{ label: string; to: string; icon?: string }>
}>()

const route = useRoute()
const router = useRouter()
const auth = useAuthStore()

async function logout() {
  await auth.logout()
  await router.push('/login/member')
}
</script>

<template>
  <aside class="fitnez-sidebar fixed inset-y-0 left-0 z-30 hidden w-72 border-r border-white/10 lg:block">
    <div class="flex h-full flex-col">
      <div class="p-6">
        <div class="rounded-[1.4rem] bg-white/8 p-4">
          <p class="text-xs font-black uppercase tracking-[0.35em] text-[#DB854F]">Fitnez</p>
          <h1 class="mt-2 text-2xl font-black text-white">{{ title }}</h1>
          <p class="mt-1 text-sm font-semibold capitalize text-white/45">{{ role }} workspace</p>
        </div>
      </div>

      <nav class="fitnez-scrollbar flex-1 space-y-1 overflow-y-auto px-4 pb-4">
        <RouterLink
          v-for="item in items"
          :key="item.to"
          :to="item.to"
          :class="[
            'flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition',
            route.path === item.to
              ? 'bg-[#F4E8E3] text-black'
              : 'text-white/70 hover:bg-white/10 hover:text-white',
          ]"
        >
          <span class="text-lg">{{ item.icon || '•' }}</span>
          <span>{{ item.label }}</span>
        </RouterLink>
      </nav>

      <div class="p-4">
        <button type="button" class="block w-full rounded-2xl bg-white/10 px-4 py-3 text-left text-sm font-bold text-white/80 hover:bg-white/15" @click="logout">
          Logout / Switch Account
        </button>
      </div>
    </div>
  </aside>
</template>
