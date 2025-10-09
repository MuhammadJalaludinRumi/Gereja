<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useInvoices } from '~/composables/useInvoices'
import { useRouter } from '#app'

const router = useRouter()
const { createInvoice } = useInvoices()

const form = ref({
  organization_id: null as number | null,
  date: '',
  current_expiry: '',
  new_expiry: '',
  total: 0
})

const organizations = ref([])
const loading = ref(true)
const saving = ref(false)
const serverError = ref<string | null>(null)

const selectedOrganization = computed(() => {
  return organizations.value.find((org: any) => org.id === Number(form.value.organization_id))
})

const formatCurrency = (value: number) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(value)
}

onMounted(async () => {
  try {
    const orgsRes = await $fetch('http://localhost:8000/api/organizations')
    organizations.value = orgsRes || []
  } catch (err: any) {
    console.error(err)
    serverError.value = 'Gagal memuat data organizations'
  } finally {
    loading.value = false
  }
})

const submit = async () => {
  serverError.value = null

  if (!form.value.organization_id) {
    serverError.value = 'Organization wajib dipilih'
    return
  }

  if (!form.value.date) {
    serverError.value = 'Tanggal invoice wajib diisi'
    return
  }

  saving.value = true

  try {
    await createInvoice(form.value)
    router.push('/invoices')
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
        Tambah Invoice
      </h1>
      <UButton
        to="/invoices"
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
      <form @submit.prevent="submit" class="space-y-6">
        <!-- Organization -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            Organization <span class="text-red-500">*</span>
          </label>
          <select
            v-model.number="form.organization_id"
            required
            class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
          >
            <option :value="null" disabled>Pilih Organization</option>
            <option
              v-for="org in organizations"
              :key="org.id"
              :value="org.id"
            >
              {{ org.name }}
            </option>
          </select>
          <UBadge v-if="selectedOrganization" color="blue" variant="soft" class="mt-2">
            {{ selectedOrganization.name }}
          </UBadge>
        </div>

        <!-- Date -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            Invoice Date <span class="text-red-500">*</span>
          </label>
          <input
            v-model="form.date"
            type="date"
            required
            class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
          />
        </div>

        <!-- Current Expiry & New Expiry Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
              Current Expiry
            </label>
            <input
              v-model="form.current_expiry"
              type="datetime-local"
              class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
            />
          </div>

          <div>
            <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
              New Expiry
            </label>
            <input
              v-model="form.new_expiry"
              type="datetime-local"
              class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
            />
          </div>
        </div>

        <!-- Total -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            Total
          </label>
          <input
            v-model.number="form.total"
            type="number"
            step="1"
            placeholder="0"
            class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
          />
          <p v-if="form.total > 0" class="text-xs mt-2" style="color: var(--ui-text-dimmed);">
            Preview: {{ formatCurrency(form.total) }}
          </p>
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
            :label="saving ? 'Saving...' : 'Save Invoice'"
          />
          <UButton
            color="gray"
            variant="soft"
            icon="i-heroicons-x-mark"
            label="Cancel"
            @click="router.push('/invoices')"
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
          <p>Field yang ditandai dengan <span class="text-red-500">*</span> wajib diisi. Current expiry dan new expiry menggunakan format datetime untuk pencatatan yang lebih akurat.</p>
        </div>
      </div>
    </UCard>

    <!-- Summary Card -->
    <UCard v-if="selectedOrganization" class="mt-4" :ui="{ body: { padding: 'p-4' } }">
      <div class="flex items-start gap-3">
        <span class="text-2xl">📄</span>
        <div class="text-sm">
          <p class="font-semibold mb-2" style="color: var(--ui-text-highlighted);">Invoice Summary</p>
          <div class="space-y-1" style="color: var(--ui-text-dimmed);">
            <p>Organization: <span class="font-medium" style="color: var(--ui-text);">{{ selectedOrganization.name }}</span></p>
            <p v-if="form.date">Date: <span class="font-medium" style="color: var(--ui-text);">{{ form.date }}</span></p>
            <p v-if="form.total > 0">Total: <span class="font-medium" style="color: var(--ui-text);">{{ formatCurrency(form.total) }}</span></p>
          </div>
        </div>
      </div>
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
