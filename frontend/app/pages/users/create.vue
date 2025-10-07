<script setup lang="ts">
import { ref } from 'vue'
import { useUsers } from '~/composables/useUsers'

const { createUser } = useUsers()
const router = useRouter()

const form = ref({
  username: '',
  password: '',
  name: '',
  is_active: 1,
  role_id: 1
})

const submit = async () => {
  try {
    await createUser(form.value)
    alert('User berhasil dibuat!')
    router.push('/users')
  } catch (err: any) {
    console.error('Gagal bikin user:', err)
    alert('Gagal membuat user, cek input / koneksi!')
  }
}

</script>

<template>
  <div class="p-6 max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-4">Create User</h1>

    <form @submit.prevent="submit" class="flex flex-col gap-3">
      <input
        v-model="form.username"
        placeholder="Username"
        class="border p-2 rounded"
      />
      <input
        v-model="form.password"
        type="password"
        placeholder="Password"
        class="border p-2 rounded"
      />
      <input
        v-model="form.name"
        placeholder="Name"
        class="border p-2 rounded"
      />
      <input
        v-model.number="form.role_id"
        type="number"
        placeholder="Role ID"
        class="border p-2 rounded"
      />
      <select v-model="form.is_active" class="border p-2 rounded">
        <option :value="1">Active</option>
        <option :value="0">Inactive</option>
      </select>

      <button class="bg-blue-500 text-white py-2 rounded">Save</button>
    </form>
  </div>
</template>
