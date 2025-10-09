<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useUsers } from '~/composables/useUsers'
import { useRuntimeConfig } from '#imports'

const router = useRouter()
const route = useRoute()
const { updateUser } = useUsers()
const api = useRuntimeConfig().public.apiBase

const user = ref({
  username: '',
  password: '',
  name: '',
  is_active: 1,
  role_id: 1
})

const loading = ref(false)
const error = ref<string | null>(null)

onMounted(async () => {
  try {
    const { data } = await useFetch(`${api}/users/${route.params.id}`)
    user.value = data.value
  } catch (err) {
    error.value = 'Gagal memuat data user'
  }
})

const save = async () => {
  loading.value = true
  try {
    await updateUser(route.params.id, user.value)
    router.push('/users')
  } catch (err) {
    error.value = 'Gagal mengupdate user'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="p-6 w-full" style="color: var(--ui-text); background: var(--ui-bg);">
    <UCard class="max-w-lg mx-auto">
      <h1 class="text-2xl font-bold mb-4">Edit Pengguna</h1>

      <form @submit.prevent="save" class="flex flex-col gap-4">
        <UInput v-model="user.username" placeholder="Username" />
        <UInput
          v-model="user.password"
          type="password"
          placeholder="Password baru (opsional)"
        />
        <UInput v-model="user.name" placeholder="Nama Lengkap" />
        <UInput v-model.number="user.role_id" type="number" placeholder="Role ID" />

        <select v-model="user.is_active" class="border p-2 rounded">
          <option :value="1">Active</option>
          <option :value="0">Inactive</option>
        </select>

        <div class="flex gap-3 mt-4">
          <UButton type="submit" :loading="loading" color="yellow" label="Update" />
          <UButton color="gray" variant="soft" label="Kembali" @click="router.push('/users')" />
        </div>

        <p v-if="error" class="text-red-500 text-sm mt-2">{{ error }}</p>
      </form>
    </UCard>
  </div>
</template>
