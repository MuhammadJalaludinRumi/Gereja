<template>
    <div class="p-4">
      <h1 class="text-2xl font-bold mb-4">Edit ACL</h1>
  
      <form @submit.prevent="save" class="max-w-sm">
        <label class="block mb-2">Nama:</label>
        <input v-model="form.name" type="text" class="border rounded p-2 w-full mb-4" required />
  
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Perbarui
        </button>
        <NuxtLink to="/acls" class="ml-2 text-gray-600 hover:underline">Kembali</NuxtLink>
      </form>
    </div>
  </template>
  
  <script setup lang="ts">
  import { useAcls } from '~/composables/useAcls'
  import { useRoute, useRouter } from 'vue-router'
  
  const { fetchById, update, acl } = useAcls()
  const route = useRoute()
  const router = useRouter()
  const form = reactive({ name: '' })
  
  onMounted(async () => {
    await fetchById(Number(route.params.id))
    form.name = acl.value?.name || ''
  })
  
  const save = async () => {
    await update(Number(route.params.id), form)
    router.push('/acls')
  }
  </script>
  