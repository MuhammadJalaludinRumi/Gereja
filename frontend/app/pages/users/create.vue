<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useUsers } from '~/composables/useUsers'

const router = useRouter()
const { createUser } = useUsers()

const form = ref({
  username: '',
  password: '',
  name: '',
  is_active: 1,
  role_id: 1
})

const saving = ref(false)
const error = ref<string | null>(null)

const save = async () => {
  if (!form.value.username || !form.value.password) {
    error.value = 'Username dan Password wajib diisi'
    return
  }

  saving.value = true
  try {
    await createUser(form.value)
    router.push('/users')
  } catch (err) {
    error.value = 'Gagal menyimpan user'
  } finally {
    saving.value = false
  }
}
</script>

<template>
  <div class="p-6 w-full" style="color: var(--ui-text); background: var(--ui-bg);">
    <UCard class="max-w-lg mx-auto">
      <h1 class="text-2xl font-bold mb-4">Tambah Pengguna Baru</h1>

      <form @submit.prevent="save" class="flex flex-col gap-4">
        <UInput v-model="form.username" placeholder="Username" />
        <UInput v-model="form.password" type="password" placeholder="Password" />
        <UInput v-model="form.name" placeholder="Nama Lengkap" />
        <UInput v-model.number="form.role_id" type="number" placeholder="Role ID" />

        <select v-model="form.is_active" class="border p-2 rounded">
          <option :value="1">Active</option>
          <option :value="0">Inactive</option>
        </select>

        <div class="flex gap-3 mt-4">
          <UButton type="submit" :loading="saving" color="primary" label="Simpan" />
          <UButton color="gray" variant="soft" label="Batal" @click="router.push('/users')" />
        </div>

        <p v-if="error" class="text-red-500 text-sm mt-2">{{ error }}</p>
      </form>
    </UCard>
  </div>
</template>
