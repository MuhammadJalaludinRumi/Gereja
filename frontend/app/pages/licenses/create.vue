<template>
    <div class="p-6 max-w-md mx-auto">
      <h1 class="text-2xl font-semibold mb-4">Tambah Lisensi</h1>
      <form @submit.prevent="save">
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
          class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
        >
          Simpan
        </button>
      </form>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref } from 'vue'
  import { useRouter } from 'vue-router'
  import { useLicenses } from '~/composables/useLicenses'
  
  const router = useRouter()
  const { createLicense } = useLicenses()
  
  const form = ref({
    name: '',
    price: '',
  })
  
  const save = async () => {
    await createLicense(form.value)
    router.push('/licenses')
  }
  </script>
  