<script setup>
import { ref, onMounted } from 'vue'
import WorkspaceLayout from '../../components/layout/WorkspaceLayout.vue'
import { memberSidebarItems } from '../../components/layout/sidebarItems'
import FitnezCard from '../../components/ui/FitnezCard.vue'
import api from '@/api/axios'

const trainers = ref([])
const loading = ref(true)

const fetchTrainers = async () => {
  try {
    const { data } = await api.get('/trainers')
    trainers.value = data
  } catch (error) {
    console.error('Gagal mengambil data trainer:', error)
  } finally {
    loading.value = false
  }
}

const hireTrainer = async (trainerId) => {
  if (!confirm('Apakah Anda yakin ingin menyewa trainer ini?')) return

  try {
    await api.post('/trainers/hire', {
      trainer_id: trainerId,
      session_type: 'Personal Coaching'
    })
    window.showFitnezToast('Permintaan penyewaan telah dikirim ke Trainer!', 'success')
  } catch (error) {
    console.error('Gagal menyewa trainer:', error)
    window.showFitnezToast('Gagal mengirim permintaan penyewaan.', 'error')
  }
}

onMounted(fetchTrainers)
</script>

<template>
  <WorkspaceLayout
    role="member"
    sidebar-title="Member"
    title="Hire a Trainer"
    subtitle="Cari dukungan pelatih profesional untuk target kebugaran Anda."
    :sidebar-items="memberSidebarItems"
  >
    <div v-if="loading" class="p-12 text-center text-muted">Memuat daftar trainer...</div>
    
    <div v-else-if="trainers.length === 0" class="p-12 text-center card bg-white shadow-sm rounded-2xl">
      <p class="text-muted">Tidak ada trainer tersedia saat ini.</p>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
      <div v-for="trainer in trainers" :key="trainer.id" class="trainer-hire-card card flex flex-col items-center p-6 text-center">
        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center text-3xl mb-4">🏋️</div>
        <h3 class="text-xl font-bold">{{ trainer.full_name }}</h3>
        <p class="text-muted text-sm mb-4">{{ trainer.email }}</p>
        <button @click="hireTrainer(trainer.id)" class="button button-primary w-full">Sewa Sekarang</button>
      </div>
    </div>

    <div class="mt-10">
      <h3 class="title-sm mb-6">Layanan Yang Tersedia</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <FitnezCard>
          <p class="eyebrow">Personal</p>
          <h4 class="font-bold">Coaching</h4>
          <p class="text-muted text-sm mt-2">Bimbingan teknik, struktur latihan, dan perencanaan progres secara personal.</p>
        </FitnezCard>
        
        <FitnezCard>
          <p class="eyebrow">Booking</p>
          <h4 class="font-bold">Sesi</h4>
          <p class="text-muted text-sm mt-2">Pesan jadwal sesi latihan yang tersedia sesuai waktu luang Anda.</p>
        </FitnezCard>

        <FitnezCard>
          <p class="eyebrow">Goal</p>
          <h4 class="font-bold">Custom Fitness Goal</h4>
          <p class="text-muted text-sm mt-2">
            Match trainer support with cutting, bulking, strength, or general health goals.
          </p>
        </FitnezCard>
      </div>
    </div>
  </WorkspaceLayout>
</template>
