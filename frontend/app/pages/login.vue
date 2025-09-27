<!-- pages/login.vue -->
<script setup lang="ts">
definePageMeta({
  ssr: false,
  layout: 'auth'
})

import { ref } from 'vue'
const username = ref('')  // ganti dari email
const password = ref('')
const { login } = useAuth()
const loading = ref(false)
const error = ref('')

async function submit() {
  loading.value = true
  error.value = ''

  try {
    // Kirim username alih-alih email
    await login(username.value, password.value)

    // Redirect ke dashboard atau home
    await navigateTo('/')

  } catch (err: any) {
    error.value = err.message || 'Username atau password salah'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md mx-auto">
      <UCard>
        <template #header>
          <h1 class="text-xl font-semibold text-center">Login</h1>
        </template>

        <form @submit.prevent="submit" class="space-y-6">
          <!-- Error message -->
          <div v-if="error" class="p-3 bg-red-100 border border-red-300 text-red-700 rounded">
            {{ error }}
          </div>

          <div>
            <UInput
              v-model="username"
              type="text"
              placeholder="Username"
              size="lg"
              required
            />
          </div>

          <div>
            <UInput
              v-model="password"
              type="password"
              placeholder="Password"
              size="lg"
              required
            />
          </div>

          <UButton
            :loading="loading"
            type="submit"
            block
            size="lg"
            class="w-full"
            :disabled="loading"
          >
            {{ loading ? 'Login...' : 'Login' }}
          </UButton>
        </form>
      </UCard>
    </div>
  </div>
</template>
