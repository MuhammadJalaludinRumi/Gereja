<template>
    <div class="p-6 max-w-md mx-auto">
      <h1 class="text-2xl font-semibold mb-4">Edit Lisensi</h1>
  
      <form @submit.prevent="update">
        <div class="mb-4">
          <label class="block font-medium mb-1">Nama Lisensi</label>
          <input
            v-model="form.name"
            type="text"
            class="w-full p-2 border border-gray-300 rounded"
            required
          />
        </div>
  
        <div class="mb-4">
          <label class="block font-medium mb-1">Harga (Rp)</label>
          <input
            v-model="form.price"
            type="number"
            step="0.01"
            class="w-full p-2 border border-gray-300 rounded"
            required
          />
        </div>
  
        <button
          type="submit"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
        >
          Update
        </button>
      </form>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref, onMounted } from 'vue'
  import { useRouter, useRoute } from 'vue-router'
  import { useLicenses } from '~/composables/useLicenses'
  
  const route = useRoute()
  const router = useRouter()
  const { getLicense, updateLicense } = useLicenses()
  
  const form = ref({
    name: '',
    price: '',
  })
  
  const loadLicense = async () => {
    const data = await getLicense(route.params.id as string)
    form.value = {
      name: data.name,
      price: data.price,
    }
  }
  
  const update = async () => {
    await updateLicense(route.params.id as string, form.value)
    router.push('/licenses')
  }
  
  onMounted(() => {
    loadLicense()
  })
  </script>
  