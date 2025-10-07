<template>
    <div class="p-4">
      <h1 class="text-2xl font-bold mb-4">Daftar ACL</h1>
      <NuxtLink to="/acls/create" class="bg-blue-500 text-white px-3 py-1 rounded">Tambah</NuxtLink>
  
      <div v-if="loading" class="mt-4">Memuat data...</div>
      <div v-else>
        <table class="w-full border mt-4">
          <thead class="bg-gray-100">
            <tr>
              <th class="border p-2">ID</th>
              <th class="border p-2">Nama</th>
              <th class="border p-2">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in acls" :key="item.id">
              <td class="border p-2">{{ item.id }}</td>
              <td class="border p-2">{{ item.name }}</td>
              <td class="border p-2">
                <NuxtLink :to="`/acls/${item.id}`" class="text-blue-600 hover:underline">Edit</NuxtLink>
                <button @click="hapus(item.id)" class="text-red-600 ml-3">Hapus</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { useAcls } from '~/composables/useAcls'
  
  const { acls, fetchAll, remove, loading } = useAcls()
  
  onMounted(fetchAll)
  
  const hapus = async (id: number) => {
    if (confirm("Yakin ingin menghapus ACL ini?")) {
      await remove(id)
      await fetchAll()
    }
  }
  </script>
  