<!-- pages/organizationLicense/[id].vue -->
<script setup lang="ts">
import { reactive, ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useOrganizationLicenses } from '~/composables/useOrganizationLicenses'

const route = useRoute()
const router = useRouter()
const { update, getById } = useOrganizationLicenses()
const api = useRuntimeConfig().public.apiBase

const form = reactive({
  organization_id: null as number | null,
  license_id: null as number | null,
  max_member: 0,
  is_active: false,
  expiry: '',
})

const organizations = ref<{ id: number; name: string }[]>([])
const licenses = ref<{ id: number; name: string }[]>([])
const loading = ref(true)
const saving = ref(false)
const serverError = ref<string | null>(null)

const selectedOrg = computed(() =>
  organizations.value.find(o => o.id === form.organization_id)
)
const selectedLicense = computed(() =>
  licenses.value.find(l => l.id === form.license_id)
)

onMounted(async () => {
  try {
    const data = await getById(Number(route.params.id))
    form.organization_id = data.organization_id
    form.license_id = data.license_id
    form.max_member = data.max_member
    form.is_active = !!data.is_active
    form.expiry = data.expiry?.replace(' ', 'T') || ''

    // ambil data dropdown
    const orgRes = await $fetch(`${api}/organizations`)
    const licRes = await $fetch(`${api}/licenses`)
    organizations.value = Array.isArray(orgRes)
      ? orgRes.sort((a, b) => a.name.localeCompare(b.name))
      : []
    licenses.value = Array.isArray(licRes)
      ? licRes.sort((a, b) => a.name.localeCompare(b.name))
      : []
  } catch (err: any) {
    console.error(err)
    serverError.value = err.message || 'Gagal memuat data.'
  } finally {
    loading.value = false
  }
})

const updateData = async () => {
  serverError.value = null
  const id = Number(route.params.id)

  if (!form.organization_id) {
    serverError.value = 'Organization wajib dipilih.'
    return
  }
  if (!form.license_id) {
    serverError.value = 'License wajib dipilih.'
    return
  }

  saving.value = true
  try {
    await update(id, {
      organization_id: form.organization_id,
      license_id: form.license_id,
      max_member: Number(form.max_member),
      is_active: !!form.is_active,
      expiry: form.expiry,
    })
    router.push('/organizationLicense')
  } catch (err: any) {
    console.error(err)
    serverError.value = err.message || 'Gagal update data.'
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
        Edit Organization License
      </h1>
      <UButton to="/organizationLicense" icon="i-heroicons-arrow-left" color="gray" variant="soft" label="Back" />
    </div>

    <!-- Loading -->
    <div v-if="loading" class="mb-4 text-sm text-gray-400">Loading...</div>

    <!-- Form -->
    <UCard v-else :ui="{ body: { padding: 'p-6' } }">
      <form @submit.prevent="updateData" class="space-y-6">
        <!-- Organization Select -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            Organization
          </label>
          <select v-model.number="form.organization_id" class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);">
            <option value="" disabled>Pilih Organization</option>
            <option v-for="org in organizations" :key="org.id" :value="org.id">
              {{ org.name }}
            </option>
          </select>
        </div>

        <!-- License Select -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            License
          </label>
          <select v-model.number="form.license_id" class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);">
            <option value="" disabled>Pilih License</option>
            <option v-for="l in licenses" :key="l.id" :value="l.id">
              {{ l.name }}
            </option>
          </select>
        </div>

        <!-- Max Member -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            Max Member
          </label>
          <input v-model.number="form.max_member" type="number" min="1" placeholder="Masukkan batas anggota"
            class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);" />
        </div>

        <!-- Active Checkbox -->
        <div class="flex items-center gap-2">
          <input v-model="form.is_active" type="checkbox" class="w-4 h-4" />
          <span class="text-sm">Active License</span>
        </div>

        <!-- Expiry -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            Expiry Date
          </label>
          <input v-model="form.expiry" type="datetime-local"
            class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);" />
        </div>

        <!-- Error -->
        <div v-if="serverError" class="px-4 py-3 rounded-lg text-sm"
          style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #ef4444;">
          {{ serverError }}
        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-3 pt-2">
          <UButton type="submit" :loading="saving" :disabled="saving" color="primary" icon="i-heroicons-check-circle"
            :label="saving ? 'Updating...' : 'Update'" />
          <UButton color="gray" variant="soft" icon="i-heroicons-x-mark" label="Cancel"
            @click="router.push('/organizationLicense')" />
        </div>
      </form>
    </UCard>

    <!-- Info Card -->
    <UCard class="mt-6" :ui="{ body: { padding: 'p-4' } }">
      <p class="text-sm" style="color: var(--ui-text-dimmed);">
        <strong>Note:</strong> Pastikan memilih organisasi & license yang benar, dan atur tanggal kedaluwarsa dengan
        tepat.
      </p>
    </UCard>
  </div>
</template>

<style scoped>
select:focus,
input:focus {
  outline: none;
  border-color: var(--ui-primary);
}
</style>
