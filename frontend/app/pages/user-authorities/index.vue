<!-- pages/user-authorities/index.vue -->
<template>
    <div class="p-6">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">User Authorities</h1>
        <NuxtLink to="/user-authorities/create" class="bg-blue-600 text-white px-4 py-2 rounded">+ New</NuxtLink>
      </div>
  
      <div v-if="loading" class="mb-4">Loading...</div>
      <div v-if="error" class="text-red-600 mb-4">{{ error }}</div>
  
      <table v-if="!loading && userAuthorities.length" class="w-full border table-auto text-sm">
        <thead>
          <tr class="bg-gray-100">
            <th class="p-2 border">ID</th>
            <th class="p-2 border">User</th>
            <th class="p-2 border">Role</th>
            <th class="p-2 border">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="ua in userAuthorities" :key="ua.id" class="border-t">
            <td class="p-2 border">{{ ua.id }}</td>
            <td class="p-2 border">{{ ua.user }}</td>
            <td class="p-2 border">{{ ua.role }}</td>
            <td class="p-2 border">
              <NuxtLink :to="`/user-authorities/${ua.id}`" class="text-blue-500 mr-2">Edit</NuxtLink>
              <button @click="handleDelete(ua.id)" class="text-red-500">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
  
      <div v-if="!loading && !userAuthorities.length" class="text-gray-600 mt-4">
        No data.
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { onMounted } from 'vue'
  import { useUserAuthorities } from '~/composables/useUserAuthorities'
  
  const { userAuthorities, loading, error, fetchAll, remove } = useUserAuthorities()
  
  const handleDelete = async (id: number) => {
    if (!confirm('Yakin hapus data ini?')) return
    try {
      await remove(id)
      await fetchAll()
    } catch (err: any) {
      alert(err.message || 'Gagal menghapus')
    }
  }
  
  onMounted(() => {
    fetchAll().catch(() => {})
  })
  </script>
  