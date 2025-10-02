<script setup>
import { ref, onMounted } from 'vue'

const provinces = ref([])
const name = ref('')
const api = 'http://localhost:8000/api/province' // sesuaikan URL backend

// State untuk modal update
const showEditModal = ref(false)
const editProvince = ref({ id: null, name: '' })

// State untuk modal delete
const showDeleteModal = ref(false)
const deleteTarget = ref({ id: null, name: '' })

// Ambil data
const fetchProvinces = async () => {
  const res = await $fetch(api)
  provinces.value = res
}

// Tambah data
const addProvince = async () => {
  if (!name.value) return
  await $fetch(api, {
    method: 'POST',
    body: { name: name.value }
  })
  name.value = ''
  fetchProvinces()
}

// Buka modal edit
const openEditModal = (province) => {
  editProvince.value = { ...province }
  showEditModal.value = true
}

// Simpan update
const saveUpdate = async () => {
  await $fetch(`${api}/${editProvince.value.id}`, {
    method: 'PUT',
    body: { name: editProvince.value.name }
  })
  showEditModal.value = false
  fetchProvinces()
}

// Buka modal delete
const openDeleteModal = (province) => {
  deleteTarget.value = { ...province }
  showDeleteModal.value = true
}

// Konfirmasi delete
const confirmDelete = async () => {
  await $fetch(`${api}/${deleteTarget.value.id}`, { method: 'DELETE' })
  showDeleteModal.value = false
  fetchProvinces()
}

onMounted(fetchProvinces)
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Provinces</h1>

    <!-- Form Tambah -->
    <div class="mb-4 flex">
      <input v-model="name" placeholder="Nama Province" class="border px-2 py-1 rounded w-64" />
      <button @click="addProvince" class="bg-blue-500 text-white px-3 py-1 ml-2 rounded">Tambah</button>
    </div>

    <!-- List Province -->
    <ul>
      <li v-for="province in provinces" :key="province.id" class="mb-2 flex items-center">
        <span class="flex-1">{{ province.name }}</span>
        <button @click="openEditModal(province)" class="bg-green-500 text-white px-3 py-1 ml-2 rounded">Edit</button>
        <button @click="openDeleteModal(province)" class="bg-red-500 text-white px-3 py-1 ml-2 rounded">Hapus</button>
      </li>
    </ul>

    <!-- Modal Edit -->
    <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-xl font-bold mb-4">Edit Province</h2>
        <input v-model="editProvince.name" class="border px-3 py-2 w-full mb-4 rounded" />
        <div class="flex justify-end">
          <button @click="showEditModal = false" class="px-4 py-2 bg-gray-300 rounded mr-2">Batal</button>
          <button @click="saveUpdate" class="px-4 py-2 bg-green-600 text-white rounded">Simpan</button>
        </div>
      </div>
    </div>

    <!-- Modal Delete -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-xl font-bold mb-4">Konfirmasi Hapus</h2>
        <p>Apakah kamu yakin ingin menghapus <strong>{{ deleteTarget.name }}</strong>?</p>
        <div class="flex justify-end mt-4">
          <button @click="showDeleteModal = false" class="px-4 py-2 bg-gray-300 rounded mr-2">Batal</button>
          <button @click="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded">Hapus</button>
        </div>
      </div>
    </div>
  </div>
</template>
