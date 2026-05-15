<script setup lang="ts">
import { onMounted } from 'vue'
import RoleLayout from '../../components/layout/RoleLayout.vue'
import { trainerSidebarItems } from '../../components/layout/sidebarItems'
import FitnezCard from '../../components/ui/FitnezCard.vue'
import { useNotificationStore } from '../../stores/notificationStore'

const store = useNotificationStore()

function formatTime(iso: string) {
  try {
    return new Intl.DateTimeFormat('id-ID', { dateStyle: 'medium', timeStyle: 'short' }).format(
      new Date(iso),
    )
  } catch {
    return iso
  }
}

async function onItem(id: number) {
  await store.markAsRead(id)
}

async function onMarkAll() {
  await store.markAllRead()
}

onMounted(async () => {
  await store.load()
  await store.loadUnreadCount()
})
</script>

<template>
  <RoleLayout
    role="trainer"
    sidebar-title="Trainer"
    title="Notifications"
    subtitle="Pengingat jadwal latihan dan pembayaran masuk."
    :sidebar-items="trainerSidebarItems"
  >
    <div style="display: flex; flex-wrap: wrap; align-items: end; justify-content: space-between; gap: 0.75rem; margin-bottom: 1.25rem;">
      <div>
        <p class="stat-label">Total Belum Dibaca</p>
        <p class="title-md">{{ store.unreadCount }}</p>
      </div>
      <button
        v-if="store.unreadCount > 0"
        type="button"
        class="button button-ghost button-small"
        :disabled="store.loading"
        @click="onMarkAll"
      >
        Tandai Semua Dibaca
      </button>
    </div>

    <!-- Loading -->
    <FitnezCard v-if="store.loading && store.items.length === 0" style="padding: 3rem; text-align: center;">
      <p class="text-muted">Memuat notifikasi...</p>
    </FitnezCard>

    <!-- Empty -->
    <FitnezCard v-else-if="store.items.length === 0" style="padding: 3rem; text-align: center;">
      <p class="text-muted">Belum ada notifikasi. Notifikasi muncul saat ada booking baru atau sesi dikonfirmasi.</p>
    </FitnezCard>

    <!-- Notification List -->
    <div v-else style="display: grid; gap: 0.75rem;">
      <FitnezCard
        v-for="n in store.items"
        :key="n.id"
        style="cursor: pointer; transition: border-color 160ms ease;"
        :style="!n.is_read ? 'border-color: var(--color-orange); background: rgba(244, 232, 227, 0.5);' : ''"
        @click="!n.is_read && onItem(n.id)"
      >
        <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
          <div
            style="width: 0.5rem; min-height: 1rem; border-radius: 999px; flex-shrink: 0; margin-top: 0.25rem;"
            :style="n.is_read ? 'background: rgba(0,0,0,0.1);' : 'background: var(--color-orange);'"
          />
          <div style="flex: 1; min-width: 0;">
            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem;">
              <p style="font-weight: 900; font-size: 0.9rem;">{{ n.title }}</p>
              <span
                v-if="n.notification_type === 'payment_in'"
                class="status status-success"
                style="font-size: 0.65rem;"
              >Pembayaran</span>
              <span
                v-else-if="n.notification_type === 'booking_request'"
                class="status status-warning"
                style="font-size: 0.65rem;"
              >Jadwal</span>
            </div>
            <p class="text-muted" style="font-size: 0.85rem; margin-top: 0.35rem;">{{ n.body }}</p>
            <p style="font-size: 0.75rem; opacity: 0.4; margin-top: 0.5rem;">{{ formatTime(n.created_at) }}</p>
          </div>
        </div>
      </FitnezCard>
    </div>
  </RoleLayout>
</template>
