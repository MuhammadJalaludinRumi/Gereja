<script setup lang="ts">
import { useOrganizations } from '~/composables/useOrganizations'
import { onMounted, reactive, ref, computed } from 'vue'
import { useRouter } from 'vue-router'

const { createOrganization } = useOrganizations()
const router = useRouter()

const form = reactive({
  name: '',
  abbreviation: '',
  address: '',
  city: '',
  latitude: null,
  longitude: null,
  phone: '',
  email: '',
  group_id: '',
  website: '',
  logo: '',
  founded: '',
  legal: '',
})

const cities = ref([])
const groups = ref([])
const loading = ref(true)
const saving = ref(false)
const serverError = ref<string | null>(null)

const selectedCity = computed(() => {
  return cities.value.find((c: any) => c.id === Number(form.city))
})

const selectedGroup = computed(() => {
  return groups.value.find((g: any) => g.id === Number(form.group_id))
})

onMounted(async () => {
  try {
    // Load cities
    const citiesRes = await $fetch('http://localhost:8000/api/city')
    cities.value = citiesRes || []

    // Load groups (yayasan)
    const groupsRes = await $fetch('http://localhost:8000/api/groups')
    groups.value = groupsRes || []
  } catch (err: any) {
    console.error(err)
    serverError.value = 'Gagal memuat data'
  } finally {
    loading.value = false
  }
})

const logoFile = ref<File | null>(null)
const logoPreview = ref<string | null>(null)

const onFileChange = (e: Event) => {
  const file = (e.target as HTMLInputElement).files?.[0] || null
  logoFile.value = file

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

const save = async () => {
  serverError.value = null

  if (!form.name) {
    serverError.value = 'Nama organization wajib diisi'
    return
  }

  saving.value = true

  try {
    const fd = new FormData()
    Object.entries(form).forEach(([key, val]) => fd.append(key, val || ''))
    if (logoFile.value) fd.append('logo', logoFile.value)

    await createOrganization(fd)
    router.push('/organizations')
  } catch (err: any) {
    console.error('Submit error:', err)
    serverError.value = err.message || 'Gagal menyimpan data'
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
        Buat Organization Baru
      </h1>
      <UButton
        to="/organizations"
        icon="i-heroicons-arrow-left"
        color="gray"
        variant="soft"
        label="Back"
      />
    </div>

    <!-- Loading -->
    <div v-if="loading" class="mb-4 text-sm text-gray-400">Loading...</div>

    <!-- Form Card -->
    <UCard v-else :ui="{ body: { padding: 'p-6' } }">
      <form @submit.prevent="save" class="space-y-6">
        <!-- Name & Abbreviation Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
              Name <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.name"
              type="text"
              required
              placeholder="Masukkan nama organization"
              class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
            />
          </div>

          <div>
            <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
              Abbreviation
            </label>
            <input
              v-model="form.abbreviation"
              type="text"
              placeholder="Singkatan"
              class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
            />
          </div>
        </div>

        <!-- Address -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            Address
          </label>
          <textarea
            v-model="form.address"
            placeholder="Masukkan alamat"
            rows="3"
            class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
          ></textarea>
        </div>

        <!-- City -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            City
          </label>
          <select
            v-model="form.city"
            class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
          >
            <option value="" disabled>Pilih Kota</option>
            <option
              v-for="city in cities"
              :key="city.id"
              :value="city.id"
            >
              {{ city.name }}
            </option>
          </select>
          <UBadge v-if="selectedCity" color="primary" variant="soft" class="mt-2">
            {{ selectedCity.name }}
          </UBadge>
        </div>

        <!-- Latitude & Longitude Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
              Latitude
            </label>
            <input
              v-model.number="form.latitude"
              type="number"
              step="any"
              placeholder="-6.200000"
              class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
            />
          </div>

          <div>
            <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
              Longitude
            </label>
            <input
              v-model.number="form.longitude"
              type="number"
              step="any"
              placeholder="106.816666"
              class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
            />
          </div>
        </div>

        <!-- Phone & Email Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
              Phone
            </label>
            <input
              v-model="form.phone"
              type="tel"
              placeholder="08123456789"
              class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
            />
          </div>

          <div>
            <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
              Email
            </label>
            <input
              v-model="form.email"
              type="email"
              placeholder="email@example.com"
              class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
            />
          </div>
        </div>

        <!-- Group (Yayasan) -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            Yayasan
          </label>
          <select
            v-model="form.group_id"
            class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
          >
            <option value="" disabled>Pilih Yayasan</option>
            <option
              v-for="group in groups"
              :key="group.id"
              :value="group.id"
            >
              {{ group.name }}
            </option>
          </select>
          <UBadge v-if="selectedGroup" color="blue" variant="soft" class="mt-2">
            {{ selectedGroup.name }}
          </UBadge>
        </div>

        <!-- Website -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            Website
          </label>
          <input
            v-model="form.website"
            type="text"
            placeholder="https://example.com"
            class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
          />
        </div>

        <!-- Logo Upload -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            Logo
          </label>
          <input
            type="file"
            accept="image/*"
            @change="onFileChange"
            class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
          />
          <!-- Preview -->
          <div v-if="logoPreview" class="mt-3">
            <img
              :src="logoPreview"
              alt="Logo Preview"
              class="h-20 w-auto rounded border"
              style="border-color: var(--ui-border);"
            />
          </div>
        </div>

        <!-- Founded & Legal Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
              Founded Date
            </label>
            <input
              v-model="form.founded"
              type="date"
              class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
            />
          </div>

          <div>
            <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
              Legal
            </label>
            <input
              v-model="form.legal"
              type="text"
              placeholder="Legal entity"
              class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
            />
          </div>
        </div>

        <!-- Error Alert -->
        <div
          v-if="serverError"
          class="px-4 py-3 rounded-lg text-sm"
          style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444;"
        >
          {{ serverError }}
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3 pt-2">
          <UButton
            type="submit"
            :loading="saving"
            :disabled="saving"
            color="primary"
            icon="i-heroicons-check-circle"
            :label="saving ? 'Saving...' : 'Save Organization'"
          />
          <UButton
            color="gray"
            variant="soft"
            icon="i-heroicons-x-mark"
            label="Cancel"
            @click="router.push('/organizations')"
          />
        </div>
      </form>
    </UCard>

    <!-- Info Card -->
    <UCard class="mt-6" :ui="{ body: { padding: 'p-4' } }">
      <div class="flex items-start gap-3">
        <span class="text-blue-400 text-lg">ℹ️</span>
        <div class="text-sm" style="color: var(--ui-text-dimmed);">
          <p class="font-semibold mb-1" style="color: var(--ui-text);">Note:</p>
          <p>Field yang ditandai dengan <span class="text-red-500">*</span> wajib diisi. Logo akan di-upload otomatis saat form disimpan.</p>
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
