<!-- pages/user-authorities/create.vue -->
<template>
    <div class="p-6 max-w-md mx-auto">
      <h1 class="text-2xl font-bold mb-4">Tambah User Authority</h1>
  
      <form @submit.prevent="save" class="space-y-4">
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
          <button type="submit" :disabled="saving" class="bg-green-600 text-white px-4 py-2 rounded">
            {{ saving ? 'Saving...' : 'Save' }}
          </button>
          <NuxtLink to="/user-authorities" class="text-gray-700">Cancel</NuxtLink>
        </div>
      </form>
    </div>
  </template>
  
  <script setup lang="ts">
  import { reactive, ref } from 'vue'
  import { useRouter } from 'vue-router'
  import { useUserAuthorities } from '~/composables/useUserAuthorities'
  
  const router = useRouter()
  const { create } = useUserAuthorities()
  
  const form = reactive({ user: null as number | null, role: null as number | null })
  const saving = ref(false)
  const serverError = ref<string | null>(null)
  
  const save = async () => {
    serverError.value = null
    // basic client validation
    if (!form.user || !form.role) {
      serverError.value = 'User and Role are required (numeric).'
      return
    }
  
    saving.value = true
    try {
      await create({ user: Number(form.user), role: Number(form.role) })
      await router.push('/user-authorities')
    } catch (err: any) {
      console.error('create failed', err)
      serverError.value = err.message || 'Server error'
    } finally {
      saving.value = false
    }
  }
  </script>
  