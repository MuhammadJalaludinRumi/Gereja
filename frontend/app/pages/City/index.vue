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

const deleteCity = async (id: number) => {
  if (!confirm('Yakin mau hapus data ini?')) return
  await $fetch(`${apiCity}/${id}`, { method: 'DELETE' })
  fetchCities()
}

onMounted(() => {
  fetchCities()
  fetchProvinces()
})
</script>

<template>
  <div class="p-6 w-full overflow-hidden" style="background: var(--ui-bg); color: var(--ui-text);">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold" style="color: var(--ui-text-highlighted);">
        Cities
      </h1>
    </div>

    <!-- Form Tambah City -->
    <UCard class="mb-6" :ui="{ body: { padding: 'p-4' } }">
      <div class="flex flex-col md:flex-row gap-3 items-center">
        <!-- Province Selector -->
        <select
          v-model="province"
          class="flex-1 rounded-lg border px-3 py-2 text-sm"
          style="background: var(--ui-bg); border-color: var(--ui-border); color: var(--ui-text);"
        >
          <option disabled value="">Pilih Province</option>
          <option v-for="prov in provinces" :key="prov.id" :value="prov.id">
            {{ prov.name }}
          </option>
        </select>

        <!-- Input Nama Kota -->
        <input
          v-model="name"
          placeholder="Nama kota..."
          class="flex-1 border rounded-lg px-3 py-2 text-sm"
          style="background: var(--ui-bg); border-color: var(--ui-border); color: var(--ui-text);"
        />

        <UButton color="primary" icon="i-heroicons-plus-circle" label="Tambah" @click="addCity" />
      </div>
    </UCard>

    <!-- Search Input -->
    <div class="mb-4">
      <input
        v-model="search"
        placeholder="Cari City / province..."
        class="w-full border rounded-lg px-3 py-2 text-sm"
        style="background: var(--ui-bg); border-color: var(--ui-border); color: var(--ui-text);"
      />
    </div>

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
            <!-- 🔥 ganti dari cities jadi filteredCities -->
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
                  @click="deleteCity(city.id)"
                />
              </td>
            </tr>

            <!-- tampilan kalau hasil search kosong -->
            <tr v-if="filteredCities.length === 0">
              <td colspan="4" class="text-center py-3 text-gray-500">Tidak ada data ditemukan</td>
            </tr>
          </tbody>
        </table>
      </div>
    </UCard>
  </div>
</template>

<style scoped>
tr:hover {
  background: var(--ui-bg-muted) !important;
  transition: background 0.2s ease;
}
</style>
