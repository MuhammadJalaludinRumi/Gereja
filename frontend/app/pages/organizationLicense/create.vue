<script setup lang="ts">
import { reactive, ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useOrganizationLicenses } from '~/composables/useOrganizationLicenses'
import { useOrganizations } from '~/composables/useOrganizations'
import { useLicenses } from '~/composables/useLicenses'

const router = useRouter()
const { create } = useOrganizationLicenses()
const { getOrganizations } = useOrganizations()
const { getLicenses } = useLicenses()

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
    const orgRes = await getOrganizations()
    const licRes = await getLicenses()
    organizations.value = orgRes.sort((a, b) => a.name.localeCompare(b.name))
    licenses.value = licRes.sort((a, b) => a.name.localeCompare(b.name))
  } catch (err: any) {
    console.error(err)
    serverError.value = 'Gagal memuat data dropdown'
  } finally {
    loading.value = false
  }
})

const submit = async () => {
  serverError.value = null
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
    await create({
      organization_id: form.organization_id,
      license_id: form.license_id,
      max_member: form.max_member,
      is_active: form.is_active,
      expiry: form.expiry,
    })
    router.push('/organizationLicense')
  } catch (err: any) {
    console.error(err)
    serverError.value = err.message || 'Gagal menyimpan data.'
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
        Tambah Organization License
      </h1>
      <UButton to="/organizationLicense" icon="i-heroicons-arrow-left" color="gray" variant="soft" label="Back" />
    </div>

    <!-- Loading -->
    <div v-if="loading" class="mb-4 text-sm text-gray-400">Loading...</div>

    <!-- Form -->
    <UCard v-else :ui="{ body: { padding: 'p-6' } }">
      <form @submit.prevent="submit" class="space-y-6">
        <!-- Organization & License -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">Organization</label>
            <select v-model.number="form.organization_id"
              class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);">
              <option value="" disabled>Pilih Organization</option>
              <option v-for="org in organizations" :key="org.id" :value="org.id">{{ org.name }}</option>
            </select>
            <UBadge v-if="selectedOrg" color="primary" variant="soft" class="mt-2">{{ selectedOrg.name }}</UBadge>
          </div>

          <div>
            <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">License</label>
            <select v-model.number="form.license_id"
              class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);">
              <option value="" disabled>Pilih License</option>
              <option v-for="l in licenses" :key="l.id" :value="l.id">{{ l.name }}</option>
            </select>
            <UBadge v-if="selectedLicense" color="blue" variant="soft" class="mt-2">{{ selectedLicense.name }}</UBadge>
          </div>
        </div>

        <!-- Max Member & Active -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">Max Member</label>
            <input v-model.number="form.max_member" type="number" min="1"
              class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);" />
          </div>
          <div class="flex items-center mt-6">
            <input v-model="form.is_active" type="checkbox" id="active" />
            <label for="active" class="ml-2 text-sm" style="color: var(--ui-text);">Active</label>
          </div>
        </div>

        <!-- Expiry -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">Expiry Date</label>
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
            :label="saving ? 'Saving...' : 'Save'" />
          <UButton color="gray" variant="soft" icon="i-heroicons-x-mark" label="Cancel"
            @click="router.push('/organizationLicense')" />
        </div>
      </form>
    </UCard>

    <!-- Info Card -->
    <UCard class="mt-6" :ui="{ body: { padding: 'p-4' } }">
      <p class="text-sm" style="color: var(--ui-text-dimmed);">
        <strong>Note:</strong> Pastikan memilih organisasi & license yang benar, dan atur tanggal kedaluwarsa dengan tepat.
      </p>
    </UCard>
  </div>
</template>

<style scoped>
input:focus,
select:focus {
  outline: none;
  border-color: var(--ui-primary);
}
</style>
