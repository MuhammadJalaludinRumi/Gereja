<template>
    <div class="p-4">
      <h1 class="text-2xl font-bold mb-4">Tambah ACL</h1>
  
      <form @submit.prevent="save" class="max-w-sm">
        <label class="block mb-2">Nama:</label>
        <input v-model="form.name" type="text" class="border rounded p-2 w-full mb-4" required />
  
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
          Simpan
        </button>
        <NuxtLink to="/acls" class="ml-2 text-gray-600 hover:underline">Kembali</NuxtLink>
      </form>
    </div>
  </template>
  
  <script setup lang="ts">
  import { useAcls } from '~/composables/useAcls'
  import { useRouter } from 'vue-router'
  
  const router = useRouter()
  const { create } = useAcls()
  
  const form = reactive({
    name: ''
  })
  
  const save = async () => {
    await create(form)
    router.push('/acls')
  }
  </script>
  