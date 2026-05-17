<script setup lang="ts">
import { onMounted } from 'vue'
import { useScheduleStore } from '../../stores/scheduleStore'
import DataTable from '../../components/shared/DataTable.vue'

const store = useScheduleStore()

onMounted(() => {
  store.load()
})
</script>

<template>
  <main class="min-h-screen bg-slate-50 p-6">
    <div class="mx-auto max-w-7xl">
      <DataTable
        title="Schedule Management"
        :columns="[
          { key: 'booking_date', label: 'Date' },
          { key: 'trainer', label: 'Trainer' },
          { key: 'member', label: 'Member' },
          { key: 'session_type', label: 'Type' },
          { key: 'status', label: 'Status' },
        ]"
        :rows="store.items"
        :loading="store.loading"
        :page="store.page"
        :last-page="store.lastPage"
        :total="store.total"
        @search="store.setSearch"
        @previous="store.page--; store.load()"
        @next="store.page++; store.load()"
      >
        <template #cell-trainer="{ row }">{{ row.trainer?.full_name }}</template>
        <template #cell-member="{ row }">{{ row.member?.full_name }}</template>
        <template #actions="{ row }">
          <button class="text-sm font-semibold text-red-600" @click="store.remove(row.id)">
            Delete
          </button>
        </template>
      </DataTable>
    </div>
  </main>
</template>
