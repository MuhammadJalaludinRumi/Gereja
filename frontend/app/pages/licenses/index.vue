<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useLicenses } from '~/composables/useLicenses'

const licenses = ref<any[]>([])
const loading = ref(false)
const tableHeaders = ['Nama Lisensi', 'Harga (Rp)', 'Dibuat Pada']

const { getLicenses, deleteLicense } = useLicenses()

// Ambil data lisensi
const fetchLicenses = async () => {
  loading.value = true
  try {
    const data = await getLicenses()
    licenses.value = Array.isArray(data) ? data : []
  } catch (error) {
    console.error('Gagal memuat data licenses:', error)
  } finally {
    loading.value = false
  }
}

// Hapus lisensi
const handleDelete = async (id: number) => {
  if (!confirm('Apakah kamu yakin ingin menghapus lisensi ini?')) return
  try {
    await deleteLicense(id)
    await fetchLicenses()
  } catch (error) {
    console.error('Gagal menghapus lisensi:', error)
  }
}

const formatDate = (date: string) => (date ? date.split('T')[0] : '-')

onMounted(fetchLicenses)
</script>

<template>
  <div class="p-6 w-full overflow-hidden" style="color: var(--ui-text); background: var(--ui-bg);">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold" style="color: var(--ui-text-highlighted);">
        Daftar Lisensi
      </h1>
      <UButton
        to="/licenses/create"
        icon="i-heroicons-plus-circle"
        size="md"
        color="primary"
        label="Tambah Lisensi"
      />
    </div>

    <!-- Loading -->
    <div v-if="loading" class="text-gray-500 text-center py-4">Memuat data...</div>

    <!-- Table -->
    <UCard :ui="{ body: { padding: '' } }" class="relative z-0 overflow-hidden">
      <div class="overflow-x-auto w-full">
        <table class="min-w-full table-auto border-collapse">
          <thead>
            <tr>
              <th
                v-for="head in tableHeaders"
                :key="head"
                class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap"
              >
                {{ head }}
              </th>
              <th class="px-3 py-3 text-center text-xs font-semibold uppercase whitespace-nowrap">Aksi</th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="license in licenses"
              :key="license.id"
              class="transition-colors hovered-row"
            >
              <td class="px-3 py-3 text-sm font-medium whitespace-nowrap">{{ license.name }}</td>
              <td class="px-3 py-3 text-sm whitespace-nowrap">Rp {{ license.price.toLocaleString() }}</td>
              <td class="px-3 py-3 text-sm whitespace-nowrap">{{ formatDate(license.created_at) }}</td>

              <td class="px-3 py-3 text-sm whitespace-nowrap text-center">
                <div class="flex justify-center gap-2">
                  <UButton
                    :to="`/licenses/${license.id}`"
                    icon="i-heroicons-pencil-square"
                    size="xs"
                    color="blue"
                    variant="soft"
                    label="Edit"
                  />
                  <UButton
                    @click.stop="handleDelete(license.id)"
                    icon="i-heroicons-trash"
                    size="xs"
                    color="red"
                    variant="soft"
                    label="Delete"
                  />
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </UCard>
  </div>
</template>
