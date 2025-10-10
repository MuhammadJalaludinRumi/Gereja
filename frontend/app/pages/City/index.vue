<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

const apiCity = 'http://localhost:8000/api/city'
const apiProvince = 'http://localhost:8000/api/province'

// State
const cities = ref<any[]>([])
const provinces = ref<any[]>([])
const name = ref('')
const province = ref('')
const search = ref('')

// Modal delete
const showDeleteModal = ref(false)
const deleteTarget = ref<any | null>(null)

const fetchCities = async () => {
  cities.value = await $fetch(apiCity)
}

const fetchProvinces = async () => {
  provinces.value = await $fetch(apiProvince)
}

// 🧠 live search computed
const filteredCities = computed(() => {
  if (!search.value) return cities.value
  const keyword = search.value.toLowerCase()
  return cities.value.filter(city =>
    city.name.toLowerCase().includes(keyword) ||
    city.province_name.toLowerCase().includes(keyword)
  )
})

const addCity = async () => {
  if (!name.value || !province.value) {
    alert('Isi nama kota dan pilih province dulu bro 🙏')
    return
  }

  await $fetch(apiCity, {
    method: 'POST',
    body: {
      name: name.value,
      province: province.value
    }
  })

  name.value = ''
  province.value = ''
  await fetchCities()
}

// ganti confirm browser dengan modal
const openDeleteModal = (city: any) => {
  deleteTarget.value = city
  showDeleteModal.value = true
}

const confirmDelete = async () => {
  if (!deleteTarget.value) return
  await $fetch(`${apiCity}/${deleteTarget.value.id}`, { method: 'DELETE' })
  showDeleteModal.value = false
  deleteTarget.value = null
  await fetchCities()
}

onMounted(() => {
  fetchCities()
  fetchProvinces()
})
</script>

<template>
  <div class="p-6 w-full overflow-hidden" style="background: var(--ui-bg); color: var(--ui-text);">
    <!-- Header & Form sama seperti sebelumnya... -->

    <!-- Table Data City -->
    <UCard :ui="{ body: { padding: '' } }" class="relative overflow-hidden">
      <div class="overflow-x-auto w-full">
        <table class="min-w-full table-auto border-collapse" style="color: var(--ui-text);">
          <thead style="background: var(--ui-bg-muted); border-bottom: 1px solid var(--ui-border);">
            <tr>
              <th class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap"
                style="color: var(--ui-text-highlighted);">ID</th>
              <th class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap"
                style="color: var(--ui-text-highlighted);">City</th>
              <th class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap"
                style="color: var(--ui-text-highlighted);">Province</th>
              <th class="px-3 py-3 text-center text-xs font-semibold uppercase whitespace-nowrap"
                style="color: var(--ui-text-highlighted);">Action</th>
            </tr>
          </thead>

          <tbody style="background: var(--ui-bg);">
            <tr
              v-for="city in filteredCities"
              :key="city.id"
              class="transition-colors"
              :style="{ borderBottom: '1px solid var(--ui-border)' }"
            >
              <td class="px-3 py-3 text-sm whitespace-nowrap">{{ city.id }}</td>
              <td class="px-3 py-3 text-sm whitespace-nowrap" style="color: var(--ui-text-highlighted);">
                {{ city.name }}
              </td>
              <td class="px-3 py-3 text-sm whitespace-nowrap">{{ city.province_name }}</td>
              <td class="px-3 py-3 text-center whitespace-nowrap">
                <UButton
                  color="red"
                  variant="soft"
                  size="xs"
                  icon="i-heroicons-trash"
                  label="Hapus"
                  @click="openDeleteModal(city)"
                />
              </td>
            </tr>

            <tr v-if="filteredCities.length === 0">
              <td colspan="4" class="text-center py-3 text-gray-500">Tidak ada data ditemukan</td>
            </tr>
          </tbody>
        </table>
      </div>
    </UCard>

    <!-- Modal Delete -->
    <Teleport to="body">
      <div
        v-if="showDeleteModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
      >
        <UCard class="max-w-md w-full mx-4 p-6">
          <h2 class="text-xl font-bold mb-4" style="color: var(--ui-text-highlighted);">Konfirmasi Hapus</h2>
          <p style="color: var(--ui-text);" class="mb-4">
            Yakin mau hapus kota <strong>{{ deleteTarget?.name }}</strong> dari province
            <strong>{{ deleteTarget?.province_name }}</strong>?
          </p>
          <div class="flex justify-end gap-3">
            <UButton color="gray" variant="soft" label="Batal" @click="showDeleteModal = false" />
            <UButton color="red" label="Hapus" @click="confirmDelete" />
          </div>
        </UCard>
      </div>
    </Teleport>
  </div>
</template>

<style scoped>
tr:hover {
  background: var(--ui-bg-muted) !important;
  transition: background 0.2s ease;
}
</style>
