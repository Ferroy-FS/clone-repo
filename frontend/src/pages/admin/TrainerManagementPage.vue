<script setup lang="ts">
import { onMounted } from 'vue'
import { useTrainerStore } from '../../stores/trainerStore'
import DataTable from '../../components/shared/DataTable.vue'

const store = useTrainerStore()

onMounted(() => {
  store.load()
})
</script>

<template>
  <main class="min-h-screen bg-slate-50 p-6">
    <div class="mx-auto max-w-7xl">
      <DataTable
        title="Trainer Management"
        :columns="[
          { key: 'name', label: 'Name' },
          { key: 'email', label: 'Email' },
          { key: 'specialization', label: 'Specialization' },
          { key: 'hourly_rate', label: 'Rate' },
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
        <template #cell-name="{ row }">{{ row.user?.full_name }}</template>
        <template #cell-email="{ row }">{{ row.user?.email }}</template>
        <template #actions="{ row }">
          <button class="text-sm font-semibold text-red-600" @click="store.remove(row.id)">
            Delete
          </button>
        </template>
      </DataTable>
    </div>
  </main>
</template>
