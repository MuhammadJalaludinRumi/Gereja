<script setup lang="ts">
import { ref, onMounted, computed, reactive } from 'vue'
import { useOrganizations } from '~/composables/useOrganizations'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const { getOrganization, updateOrganization, deleteOrganization } = useOrganizations()

const organization = ref<any>(null)
const cities = ref([])
const groups = ref([])
const loading = ref(true)
const saving = ref(false)
const serverError = ref<string | null>(null)
const showDeleteModal = ref(false)
const currentLogo = ref<string | null>(null)

const selectedCity = computed(() => {
  return cities.value.find((c: any) => c.id === Number(organization.value?.city))
})

const selectedGroup = computed(() => {
  return groups.value.find((g: any) => g.id === Number(organization.value?.group_id))
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

onMounted(async () => {
  try {
    // Load cities
    const citiesRes = await $fetch('http://localhost:8000/api/city')
    cities.value = citiesRes || []

    // Load groups
    const groupsRes = await $fetch('http://localhost:8000/api/groups')
    groups.value = groupsRes || []

    // Load organization data
    organization.value = await getOrganization(route.params.id as string)

    if (organization.value) {
      // Simpan logo lama
      currentLogo.value = organization.value.logo

      // Format founded date
      if (organization.value.founded) {
        organization.value.founded = organization.value.founded.split('T')[0]
      }
    }
  } catch (err: any) {
    console.error(err)
    serverError.value = 'Gagal memuat data'
  } finally {
    loading.value = false
  }
})

const update = async () => {
  serverError.value = null

  if (!organization.value.name) {
    serverError.value = 'Nama organization wajib diisi'
    return
  }

  saving.value = true

  try {
    const id = route.params.id as string
    const fd = new FormData()

    // isi semua field dulu
    Object.entries(organization.value).forEach(([key, val]) => {
      if (key !== 'logo') fd.append(key, val || '')
    })

    // tambahin file logo kalo ada
    if (logoFile.value) {
      fd.append('logo', logoFile.value)
    }

    // penting: Laravel butuh ini buat deteksi PUT
    fd.append('_method', 'PUT')

    // kirim sebagai FormData, biar backend nerima file dan text
    await updateOrganization(id, fd, true)

    router.push('/organizations')
  } catch (err: any) {
    console.error('Update error:', err)
    serverError.value = err.message || 'Gagal update data'
  } finally {
    saving.value = false
  }
}

const openDeleteModal = () => {
  showDeleteModal.value = true
}

const confirmDelete = async () => {
  try {
    await deleteOrganization(route.params.id as string)
    router.push('/organizations')
  } catch (err: any) {
    console.error('Delete error:', err)
    serverError.value = err.message || 'Gagal menghapus data'
    showDeleteModal.value = false
  }
}
</script>

<template>
  <div class="p-6 w-full overflow-hidden" style="background: var(--ui-bg); color: var(--ui-text);">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold" style="color: var(--ui-text-highlighted);">
        Edit Organization
      </h1>
      <UButton to="/organizations" icon="i-heroicons-arrow-left" color="gray" variant="soft" label="Back" />
    </div>

    <!-- Loading -->
    <div v-if="loading" class="mb-4 text-sm text-gray-400">Loading...</div>

    <!-- Form Card -->
    <UCard v-else-if="organization" :ui="{ body: { padding: 'p-6' } }">
      <form @submit.prevent="update" class="space-y-6">
        <!-- Name & Abbreviation Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
              Name <span class="text-red-500">*</span>
            </label>
            <input v-model="organization.name" type="text" required placeholder="Masukkan nama organization"
              class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);" />
          </div>

          <div>
            <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
              Abbreviation
            </label>
            <input v-model="organization.abbreviation" type="text" placeholder="Singkatan"
              class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);" />
          </div>
        </div>

        <!-- Address -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            Address
          </label>
          <textarea v-model="organization.address" placeholder="Masukkan alamat" rows="3"
            class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"></textarea>
        </div>

        <!-- City -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            City
          </label>
          <select v-model="organization.city" class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
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

        <!-- Latitude & Longitude Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
              Latitude
            </label>
            <input v-model.number="organization.latitude" type="number" step="any" placeholder="-6.200000"
              class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);" />
          </div>

          <div>
            <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
              Longitude
            </label>
            <input v-model.number="organization.longitude" type="number" step="any" placeholder="106.816666"
              class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);" />
          </div>
        </div>

        <!-- Phone & Email Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
              Phone
            </label>
            <input v-model="organization.phone" type="tel" placeholder="08123456789"
              class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);" />
          </div>

          <div>
            <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
              Email
            </label>
            <input v-model="organization.email" type="email" placeholder="email@example.com"
              class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);" />
          </div>
        </div>

        <!-- Group (Yayasan) -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            Yayasan
          </label>
          <select v-model="organization.group_id" class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);">
            <option value="" disabled>Pilih Yayasan</option>
            <option v-for="group in groups" :key="group.id" :value="group.id">
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
          <input v-model="organization.website" type="text" placeholder="https://example.com"
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
            <input v-model="organization.founded" type="date"
              class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);" />
          </div>

          <div>
            <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
              Legal
            </label>
            <input v-model="organization.legal" type="text" placeholder="Legal entity"
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
            :label="saving ? 'Updating...' : 'Update Organization'" />
          <UButton type="button" color="red" variant="soft" icon="i-heroicons-trash" label="Delete"
            @click="openDeleteModal" />
          <UButton color="gray" variant="soft" icon="i-heroicons-x-mark" label="Cancel"
            @click="router.push('/organizations')" />
        </div>
      </form>
    </UCard>

    <!-- Info Card -->
    <UCard v-if="!loading" class="mt-6" :ui="{ body: { padding: 'p-4' } }">
      <div class="flex items-start gap-3">
        <span class="text-blue-400 text-lg">ℹ️</span>
        <div class="text-sm" style="color: var(--ui-text-dimmed);">
          <p class="font-semibold mb-1" style="color: var(--ui-text);">Note:</p>
          <p>Field yang ditandai dengan <span class="text-red-500">*</span> wajib diisi. Kosongkan upload logo jika
            tidak ingin mengubah logo yang sudah ada.</p>
        </div>
      </div>
    </UCard>

    <!-- Delete Modal -->
    <Teleport to="body">
      <div v-if="showDeleteModal" class="fixed inset-0 z-[99999] flex items-center justify-center"
        style="background: rgba(0, 0, 0, 0.5);">
        <UCard class="max-w-md w-full mx-4"
          style="background: var(--ui-bg); color: var(--ui-text); border: 1px solid var(--ui-border);">
          <template #header>
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-semibold" style="color: var(--ui-text-highlighted);">
                Konfirmasi Hapus
              </h3>
              <UButton color="gray" variant="ghost" icon="i-heroicons-x-mark-20-solid"
                @click="showDeleteModal = false" />
            </div>
          </template>

          <div class="py-4">
            <p style="color: var(--ui-text);">
              Yakin ingin menghapus organization <strong>{{ organization?.name }}</strong>?
            </p>
          </div>

          <template #footer>
            <div class="flex justify-end gap-3">
              <UButton color="gray" variant="soft" label="Batal" @click="showDeleteModal = false" />
              <UButton color="red" label="Hapus" @click="confirmDelete" />
            </div>
          </template>
        </UCard>
      </div>
    </Teleport>
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
