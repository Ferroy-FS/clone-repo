<script setup lang="ts">
import { onMounted } from 'vue'
import { useAdminUserStore } from '../../stores/adminUserStore'
import DataTable from '../../components/shared/DataTable.vue'

const store = useAdminUserStore()

onMounted(() => {
  store.loadRoles()
  store.load()
})
</script>

<template>
  <main class="min-h-screen bg-slate-50 p-6">
    <div class="mx-auto max-w-7xl">
      <DataTable
        title="User Management"
        :columns="[
          { key: 'full_name', label: 'Name' },
          { key: 'email', label: 'Email' },
          { key: 'role', label: 'Role' },
          { key: 'is_active', label: 'Status' },
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
        <template #cell-role="{ row }">
          {{ row.role?.name || row.role }}
        </template>

        <template #cell-is_active="{ row }">
          <span :class="row.is_active ? 'text-green-600' : 'text-red-600'">
            {{ row.is_active ? 'Active' : 'Inactive' }}
          </span>
        </template>

        <template #actions="{ row }">
          <button class="text-sm font-semibold text-red-600" @click="store.remove(row.id)">
            Delete
          </button>
        </template>
      </DataTable>
    </div>
  </main>
</template>
