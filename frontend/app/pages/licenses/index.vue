<template>
    <div class="p-6">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">Daftar Lisensi</h1>
        <NuxtLink
          to="/licenses/create"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
        >
          + Tambah Lisensi
        </NuxtLink>
      </div>
  
      <table class="min-w-full border border-gray-300">
        <thead class="bg-gray-100">
          <tr>
            <th class="border p-2 text-left">ID</th>
            <th class="border p-2 text-left">Nama Lisensi</th>
            <th class="border p-2 text-left">Harga (Rp)</th>
            <th class="border p-2 text-center w-40">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="license in licenses"
            :key="license.id"
            class="hover:bg-gray-50"
          >
            <td class="border p-2">{{ license.id }}</td>
            <td class="border p-2">{{ license.name }}</td>
            <td class="border p-2">{{ license.price }}</td>
            <td class="border p-2 text-center space-x-2">
              <NuxtLink
                :to="`/licenses/${license.id}`"
                class="text-blue-600 hover:underline"
                >Edit</NuxtLink
              >
              <button
                @click="removeLicense(license.id)"
                class="text-red-600 hover:underline"
              >
                Hapus
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref, onMounted } from 'vue'
  import { useLicenses } from '~/composables/useLicenses'
  
  const { getLicenses, deleteLicense } = useLicenses()
  const licenses = ref<any[]>([])
  
  const loadLicenses = async () => {
    licenses.value = await getLicenses()
  }
  
  const removeLicense = async (id: number) => {
    if (confirm('Yakin ingin menghapus lisensi ini?')) {
      await deleteLicense(id)
      await loadLicenses()
    }
  }
  
  onMounted(() => {
    loadLicenses()
  })
  </script>
  