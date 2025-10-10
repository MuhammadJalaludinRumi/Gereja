<!-- pages/role/[id].vue -->
<script setup lang="ts">
import { reactive, ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useRoles } from '~/composables/useRoles'
import { useOrganizations } from '~/composables/useOrganizations'

const route = useRoute()
const router = useRouter()
const { roles, updateRole } = useRoles()
const { getOrganizations } = useOrganizations()

const form = reactive({
  name: '',
  organization_id: null as number | null,
})

const organizations = ref<{ id: number; name: string }[]>([])
const loading = ref(true)
const saving = ref(false)
const serverError = ref<string | null>(null)

const selectedOrg = computed(() =>
  organizations.value.find(o => o.id === form.organization_id)
)

onMounted(async () => {
  loading.value = true
  try {
    const api = useRuntimeConfig().public.apiBase
    const id = Number(route.params.id)

    // ambil role by id pake useFetch
    const { data, error } = await useFetch(`${api}/roles/${id}`)
    if (error.value || !data.value) throw new Error(error.value?.message || 'Role tidak ditemukan')

    form.name = data.value.name
    form.organization_id = data.value.organization_id

    // ambil organization buat dropdown
    const orgs = await getOrganizations()
    organizations.value = Array.isArray(orgs) ? orgs.sort((a, b) => a.name.localeCompare(b.name)) : []
  } catch (err: any) {
    console.error(err)
    serverError.value = err.message || 'Gagal memuat data'
  } finally {
    loading.value = false
  }
})

const updateData = async () => {
  serverError.value = null
  if (!form.name) {
    serverError.value = 'Name wajib diisi'
    return
  }
  if (!form.organization_id) {
    serverError.value = 'Organization wajib dipilih'
    return
  }

  saving.value = true
  try {
    const id = Number(route.params.id)
    await updateRole(id, {
      name: form.name,
      organization_id: form.organization_id,
    })
    router.push('/roles')
  } catch (err: any) {
    console.error(err)
    serverError.value = err.message || 'Gagal update role'
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
        Edit Role
      </h1>
      <UButton to="/roles" icon="i-heroicons-arrow-left" color="gray" variant="soft" label="Back" />
    </div>

    <!-- Loading -->
    <div v-if="loading" class="mb-4 text-sm text-gray-400">Loading...</div>

    <!-- Form -->
    <UCard v-else :ui="{ body: { padding: 'p-6' } }">
      <form @submit.prevent="updateData" class="space-y-6">
        <!-- Role Name -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            Role Name
          </label>
          <input
            v-model="form.name"
            type="text"
            placeholder="Masukkan nama role"
            class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
          />
        </div>

        <!-- Organization Dropdown -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            Organization
          </label>
          <select
            v-model.number="form.organization_id"
            class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
          >
            <option value="" disabled>Pilih Organization</option>
            <option v-for="org in organizations" :key="org.id" :value="org.id">
              {{ org.name }}
            </option>
          </select>
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
            @click="router.push('/roles')" />
        </div>
      </form>
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
