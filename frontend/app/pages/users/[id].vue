<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useUsers } from '~/composables/useUsers'
import { useRoute, useRouter, useRuntimeConfig } from '#imports'
import { useToast } from '#imports' // opsional kalau mau notifikasi

const { updateUser } = useUsers()
const route = useRoute()
const router = useRouter()
const toast = useToast()
const api = useRuntimeConfig().public.apiBase

const user = ref({
  username: '',
  password: '',
  name: '',
  is_active: 1,
  role_id: 1
})

const loading = ref(false)

onMounted(async () => {
  const { data } = await useFetch(`${api}/users/${route.params.id}`)
  user.value = data.value
})

const submit = async () => {
  loading.value = true
  try {
    await updateUser(route.params.id, user.value)
    toast.add({ title: '✅ User berhasil diupdate!' })
    router.push('/users')
  } catch (err: any) {
    console.error(err)
    toast.add({ title: '❌ Gagal update user', color: 'red' })
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="p-6 max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-4">Edit User</h1>

    <form @submit.prevent="submit" class="flex flex-col gap-3">
      <input
        v-model="user.username"
        placeholder="Username"
        class="border p-2 rounded"
      />
      <input
        v-model="user.password"
        type="password"
        placeholder="New Password (optional)"
        class="border p-2 rounded"
      />
      <input
        v-model="user.name"
        placeholder="Name"
        class="border p-2 rounded"
      />
      <input
        v-model.number="user.role_id"
        type="number"
        placeholder="Role ID"
        class="border p-2 rounded"
      />
      <select v-model="user.is_active" class="border p-2 rounded">
        <option :value="1">Active</option>
        <option :value="0">Inactive</option>
      </select>

      <button
        type="submit"
        :disabled="loading"
        class="bg-yellow-500 text-white py-2 rounded hover:bg-yellow-600 transition disabled:opacity-50"
      >
        {{ loading ? 'Updating...' : 'Update' }}
      </button>
    </form>
  </div>
</template>
