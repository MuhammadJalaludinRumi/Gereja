<!-- pages/user-authorities/[id].vue -->
<template>
    <div class="p-6 max-w-md mx-auto">
      <h1 class="text-2xl font-bold mb-4">Edit User Authority</h1>
  
      <div v-if="loading">Loading...</div>
      <div v-else>
        <form @submit.prevent="updateData" class="space-y-4">
          <div>
            <label class="block mb-1 font-medium">User ID</label>
            <input v-model.number="form.user" type="number" class="w-full p-2 border rounded" required />
          </div>
  
          <div>
            <label class="block mb-1 font-medium">Role ID</label>
            <input v-model.number="form.role" type="number" class="w-full p-2 border rounded" required />
          </div>
  
          <div v-if="serverError" class="text-red-600">{{ serverError }}</div>
  
          <div class="flex items-center space-x-3">
            <button type="submit" :disabled="saving" class="bg-blue-600 text-white px-4 py-2 rounded">
              {{ saving ? 'Updating...' : 'Update' }}
            </button>
            <NuxtLink to="/user-authorities" class="text-gray-700">Back</NuxtLink>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { reactive, ref, onMounted } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import { useUserAuthorities } from '~/composables/useUserAuthorities'
  
  const route = useRoute()
  const router = useRouter()
  const { fetchById, update, loading } = useUserAuthorities()
  
  const form = reactive({ user: null as number | null, role: null as number | null })
  const saving = ref(false)
  const serverError = ref<string | null>(null)
  
  onMounted(async () => {
    try {
      const id = Number(route.params.id)
      const data = await fetchById(id)
      // guard & copy to local form
      form.user = data?.user ?? null
      form.role = data?.role ?? null
    } catch (err: any) {
      serverError.value = err.message || 'Gagal memuat data'
    }
  })
  
  const updateData = async () => {
    serverError.value = null
    const id = Number(route.params.id)
    if (!form.user || !form.role) {
      serverError.value = 'User and Role are required.'
      return
    }
    saving.value = true
    try {
      await update(id, { user: Number(form.user), role: Number(form.role) })
      router.push('/user-authorities')
    } catch (err: any) {
      console.error('update error', err)
      serverError.value = err.message || 'Gagal update'
    } finally {
      saving.value = false
    }
  }
  </script>
  