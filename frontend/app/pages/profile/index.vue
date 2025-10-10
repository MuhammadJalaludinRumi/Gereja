<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuth } from '~/composables/useAuth'

const apiProfile = 'localhost:8000/api/user/profile'
const config = useRuntimeConfig()
const { user, fetchUser, token } = useAuth()

const form = ref({
  name: '',
  username: '',
  password: ''
})

const loading = ref(false)
const successMsg = ref('')
const errorMsg = ref('')

// Ambil data user login dari composable
const fetchProfile = async () => {
  try {
    const data = await fetchUser()
    if (!data) throw new Error('User tidak ditemukan')

    form.value.name = data.name
    form.value.username = data.username
  } catch (err) {
    console.error(err)
    errorMsg.value = 'Gagal memuat profil 😢'
  }
}

// Update username & password
const updateProfile = async () => {
  loading.value = true
  successMsg.value = ''
  errorMsg.value = ''

  try {
    await $fetch(apiProfile, {
      baseURL: config.public.apiBase,
      method: 'PUT',
      credentials: 'include',  // ⬅ penting!
      headers: {
        Accept: 'application/json'
      },
      body: {
        username: form.value.username,
        password: form.value.password
      }
    })

    await fetchProfile()
    successMsg.value = 'Profil berhasil diperbarui 🎉'
    form.value.password = ''
  } catch (err: any) {
    console.error(err)
    errorMsg.value = err?.data?.message || 'Gagal memperbarui profil 😭'
  } finally {
    loading.value = false
  }
}

onMounted(fetchProfile)
</script>

<template>
  <div class="p-6 max-w-lg mx-auto space-y-6" style="background: var(--ui-bg); color: var(--ui-text);">
    <h1 class="text-2xl font-bold mb-4" style="color: var(--ui-text-highlighted);">
      My Profile
    </h1>

    <UCard :ui="{ body: { padding: 'p-5' } }">
      <div class="space-y-4">
        <!-- Nama (readonly) -->
        <div>
          <label class="text-sm font-medium">Full Name</label>
          <input v-model="form.name" disabled
            class="w-full border rounded-lg px-3 py-2 text-sm opacity-70 cursor-not-allowed"
            style="background: var(--ui-bg-muted); border-color: var(--ui-border); color: var(--ui-text);" />
        </div>

        <!-- Username -->
        <div>
          <label class="text-sm font-medium">Username</label>
          <input v-model="form.username" placeholder="Masukkan username baru..."
            class="w-full border rounded-lg px-3 py-2 text-sm"
            style="background: var(--ui-bg); border-color: var(--ui-border); color: var(--ui-text);" />
        </div>

        <!-- Password -->
        <div>
          <label class="text-sm font-medium">Password</label>
          <input v-model="form.password" type="password" placeholder="Masukkan password baru..."
            class="w-full border rounded-lg px-3 py-2 text-sm"
            style="background: var(--ui-bg); border-color: var(--ui-border); color: var(--ui-text);" />
        </div>

        <!-- Alert -->
        <div v-if="successMsg" class="text-green-500 text-sm font-medium">
          ✅ {{ successMsg }}
        </div>
        <div v-if="errorMsg" class="text-red-500 text-sm font-medium">
          ⚠️ {{ errorMsg }}
        </div>

        <!-- Tombol Simpan -->
        <UButton color="primary" :loading="loading" icon="i-heroicons-check-circle" label="Simpan Perubahan"
          class="w-full" @click="updateProfile" />
      </div>
    </UCard>
  </div>
</template>
