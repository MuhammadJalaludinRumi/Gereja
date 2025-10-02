<script setup lang="ts">
import { ref, onMounted } from 'vue'

const apiCity = 'http://localhost:8000/api/city'
const apiProvince = 'http://localhost:8000/api/province'

// State
const cities = ref<any[]>([])
const provinces = ref<any[]>([])
const name = ref('')
const province = ref('')

// Ambil data cities
const fetchCities = async () => {
  cities.value = await $fetch(apiCity)
}

// Ambil data provinces buat combobox
const fetchProvinces = async () => {
  provinces.value = await $fetch(apiProvince)
}

// Tambah city
const addCity = async () => {
  if (!name.value || !province.value) return
  await $fetch(apiCity, {
    method: 'POST',
    body: {
      name: name.value,
      province: province.value // ini id (integer)
    }
  })
  name.value = ''
  province.value = ''
  fetchCities()
}

// Hapus city
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
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">CRUD City</h1>

    <!-- Form tambah city -->
    <div class="mb-4 flex gap-2">
      <input v-model="name" placeholder="Nama City" class="border px-2 py-1" />

      <!-- Combobox province -->
      <select v-model="province" class="border px-2 py-1">
        <option disabled value="">Pilih Province</option>
        <option v-for="prov in provinces" :key="prov.id" :value="prov.id">
          {{ prov.name }}
        </option>
      </select>


      <button @click="addCity" class="bg-blue-500 text-white px-3 py-1">Tambah</button>
    </div>

    <!-- List data city -->
    <table class="w-full border">
      <thead>
        <tr class="bg-gray-200">
          <th class="border px-2 py-1">ID</th>
          <th class="border px-2 py-1">City</th>
          <th class="border px-2 py-1">Province</th>
          <th class="border px-2 py-1">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="city in cities" :key="city.id">
          <td class="border px-2 py-1">{{ city.id }}</td>
          <td class="border px-2 py-1">{{ city.name }}</td>
          <td class="border px-2 py-1">{{ city.province_name }}</td>
          <td class="border px-2 py-1">
            <button @click="deleteCity(city.id)" class="bg-red-500 text-white px-3 py-1">
              Hapus
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
