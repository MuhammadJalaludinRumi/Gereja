<script setup lang="ts">
import { useGroups } from '~/composables/useGroups'
import { reactive, ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const { getGroup, updateGroup } = useGroups()

// state form
const form = reactive({
  name: '',
  address: '',
  city: '',
  phone: '',
  email: '',
  website: '',
  logo: '',
  founded: '',
  legal: '',
})

const cities = ref([])
const loading = ref(true)
const saving = ref(false)
const serverError = ref<string | null>(null)
const currentLogo = ref<string | null>(null)

const selectedCity = computed(() => {
  return cities.value.find((c: any) => c.id === Number(form.city))
})

const logoFile = ref<File | null>(null)
const logoPreview = ref<string | null>(null)

const onFileChange = (e: Event) => {
  const file = (e.target as HTMLInputElement).files?.[0] || null
  logoFile.value = file

  // Preview image
  if (file) {
    const reader = new FileReader()
    reader.onload = (e) => {
      logoPreview.value = e.target?.result as string
    }
    reader.readAsDataURL(file)
  } else {
    logoPreview.value = null
  }
}

// load data awal
onMounted(async () => {
  try {
    // Load cities
    const citiesRes = await $fetch('http://localhost:8000/api/city')
    cities.value = citiesRes || []

    // Load group data
    const { data } = await getGroup(Number(route.params.id))
    if (data.value) {
      Object.assign(form, data.value)
      // simpan logo lama
      currentLogo.value = form.logo
      // kalau kolom founded date, ubah ke format yyyy-mm-dd biar cocok dengan <input type="date">
      if (form.founded) {
        form.founded = form.founded.split('T')[0]
      }
    }
  } catch (err: any) {
    console.error(err)
    serverError.value = 'Gagal memuat data'
  } finally {
    loading.value = false
  }
})

const submit = async () => {
  serverError.value = null
  saving.value = true

  try {
    const fd = new FormData()
    Object.entries(form).forEach(([key, val]) => {
      if (key !== 'logo') fd.append(key, val || '')
    })
    if (logoFile.value) fd.append('logo', logoFile.value)
    fd.append('_method', 'PUT') // penting buat Laravel

    await updateGroup(Number(route.params.id), fd, true)
    router.push('/groups')
  } catch (err: any) {
    console.error('Update error:', err)
    serverError.value = err.message || 'Gagal update data'
  } finally {
    saving.value = false
  }
}
</script>

<template>
  <div class="p-6 w-full overflow-hidden" style="background: var(--ui-bg); color: var(--ui-text);">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold" style="color: var(--ui-text-highlighted);">
        Edit Group
      </h1>
      <UButton to="/groups" icon="i-heroicons-arrow-left" color="gray" variant="soft" label="Back" />
    </div>

    <!-- Loading -->
    <div v-if="loading" class="mb-4 text-sm text-gray-400">Loading...</div>

    <!-- Form Card -->
    <UCard v-else :ui="{ body: { padding: 'p-6' } }">
      <form @submit.prevent="submit" class="space-y-6">
        <!-- Name -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            Name <span class="text-red-500">*</span>
          </label>
          <input v-model="form.name" type="text" required placeholder="Masukkan nama group"
            class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);" />
        </div>

        <!-- Address -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            Address
          </label>
          <textarea v-model="form.address" placeholder="Masukkan alamat" rows="3"
            class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"></textarea>
        </div>

        <!-- City -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            City
          </label>
          <select v-model="form.city" class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);">
            <option value="" disabled>Pilih Kota</option>
            <option v-for="city in cities" :key="city.id" :value="city.id">
              {{ city.name }}
            </option>
          </select>
          <UBadge v-if="selectedCity" color="primary" variant="soft" class="mt-2">
            {{ selectedCity.name }}
          </UBadge>
        </div>

        <!-- Phone & Email Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
              Phone
            </label>
            <input v-model="form.phone" type="tel" placeholder="08123456789"
              class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);" />
          </div>

          <div>
            <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
              Email
            </label>
            <input v-model="form.email" type="email" placeholder="email@example.com"
              class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);" />
          </div>
        </div>

        <!-- Website -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            Website
          </label>
          <input v-model="form.website" type="text" placeholder="https://example.com"
            class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);" />
        </div>

        <!-- Logo Upload -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            Logo
          </label>

          <!-- Current Logo -->
          <div v-if="currentLogo && !logoPreview" class="mb-3">
            <p class="text-xs mb-2" style="color: var(--ui-text-dimmed);">Current logo:</p>
            <img :src="currentLogo" alt="Current Logo" class="h-20 w-auto rounded border"
              style="border-color: var(--ui-border);" />
          </div>

          <!-- Upload new logo -->
          <input type="file" accept="image/*" @change="onFileChange"
            class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);" />

          <!-- New Logo Preview -->
          <div v-if="logoPreview" class="mt-3">
            <p class="text-xs mb-2" style="color: var(--ui-text-dimmed);">New logo preview:</p>
            <img :src="logoPreview" alt="New Logo Preview" class="h-20 w-auto rounded border"
              style="border-color: var(--ui-border);" />
          </div>

          <p class="text-xs mt-2" style="color: var(--ui-text-dimmed);">
            Leave empty if you don't want to change the logo
          </p>
        </div>

        <!-- Founded & Legal Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
              Founded Date
            </label>
            <input v-model="form.founded" type="date" class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);" />
          </div>

          <div>
            <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
              Legal
            </label>
            <input v-model="form.legal" type="text" placeholder="Legal entity"
              class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);" />
          </div>
        </div>

        <!-- Error Alert -->
        <div v-if="serverError" class="px-4 py-3 rounded-lg text-sm"
          style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444;">
          {{ serverError }}
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3 pt-2">
          <UButton type="submit" :loading="saving" :disabled="saving" color="primary" icon="i-heroicons-check-circle"
            :label="saving ? 'Updating...' : 'Update Group'" />
          <UButton color="gray" variant="soft" icon="i-heroicons-x-mark" label="Cancel"
            @click="router.push('/groups')" />
        </div>
      </form>
    </UCard>

    <!-- Info Card -->
    <UCard class="mt-6" :ui="{ body: { padding: 'p-4' } }">
      <div class="flex items-start gap-3">
        <span class="text-blue-400 text-lg">ℹ️</span>
        <div class="text-sm" style="color: var(--ui-text-dimmed);">
          <p class="font-semibold mb-1" style="color: var(--ui-text);">Note:</p>
          <p>Field yang ditandai dengan <span class="text-red-500">*</span> wajib diisi. Kosongkan upload logo jika
            tidak ingin mengubah logo yang sudah ada.</p>
        </div>
      </div>
    </UCard>
  </div>
</template>

<style scoped>
input:focus,
select:focus,
textarea:focus {
  outline: none;
  border-color: var(--ui-primary);
}
</style>
