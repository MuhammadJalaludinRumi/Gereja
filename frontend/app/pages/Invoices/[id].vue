<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useInvoices, type Invoice } from '~/composables/useInvoices'
import { useRoute, useRouter, useRuntimeConfig } from '#app'

const route = useRoute()
const router = useRouter()
const config = useRuntimeConfig()
const { updateInvoice } = useInvoices()

const form = ref<Invoice>({
  organization_id: 0,
  date: '',
  current_expiry: '',
  new_expiry: '',
  total: 0
})

const loading = ref(true)
const saving = ref(false)
const serverError = ref<string | null>(null)
const organizationName = ref('')

const fetchInvoice = async () => {
  try {
    const { data, error } = await useFetch<Invoice>(
      `${config.public.apiBase}/invoices/${route.params.id}`
    )

    if (error.value) {
      serverError.value = 'Data invoice tidak ditemukan.'
      router.push('/invoices')
      return
    }

    if (data.value) {
      form.value = {
        organization_id: data.value.organization_id,
        date: data.value.date,
        current_expiry: data.value.current_expiry
          ? data.value.current_expiry.replace(' ', 'T')
          : '',
        new_expiry: data.value.new_expiry
          ? data.value.new_expiry.replace(' ', 'T')
          : '',
        total: data.value.total
      }

      // ambil nama organisasi (optional)
      try {
        const orgRes = await $fetch(
          `${config.public.apiBase}/organizations/${data.value.organization_id}`
        )
        organizationName.value = orgRes?.name || `Org #${data.value.organization_id}`
      } catch {
        organizationName.value = `Org #${data.value.organization_id}`
      }
    }
  } catch (e) {
    console.error(e)
    serverError.value = 'Gagal mengambil data invoice.'
  } finally {
    loading.value = false
  }
}

onMounted(fetchInvoice)

const submit = async () => {
  serverError.value = null
  saving.value = true
  try {
    await updateInvoice(Number(route.params.id), form.value)
    router.push('/invoices')
  } catch (e: any) {
    console.error(e)
    serverError.value = e.message || 'Gagal memperbarui invoice.'
  } finally {
    saving.value = false
  }
}
</script>

<template>
  <div
    class="p-6 w-full overflow-hidden"
    style="background: var(--ui-bg); color: var(--ui-text);"
  >
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold" style="color: var(--ui-text-highlighted);">
        Edit Invoice #{{ route.params.id }}
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
    <div v-if="loading" class="text-sm text-gray-400 mb-4">Loading data invoice...</div>

    <!-- Form Card -->
    <UCard v-else :ui="{ body: { padding: 'p-6' } }">
      <form @submit.prevent="submit" class="space-y-6">
        <!-- Organization Name -->
        <div>
          <label
            class="block mb-2 text-sm font-semibold"
            style="color: var(--ui-text-highlighted);"
          >
            Organization
          </label>
          <input
            type="text"
            :value="organizationName"
            readonly
            class="w-full px-3 py-2 text-sm rounded-lg cursor-not-allowed"
            style="background: var(--ui-bg-muted); border: 1px solid var(--ui-border); color: var(--ui-text-dimmed);"
          />
        </div>

        <!-- Invoice Date -->
        <div>
          <label
            class="block mb-2 text-sm font-semibold"
            style="color: var(--ui-text-highlighted);"
          >
            Invoice Date
          </label>
          <input
            v-model="form.date"
            type="datetime-local"
            class="w-full px-3 py-2 text-sm rounded-lg"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
          />
        </div>

        <!-- Expiry Dates -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label
              class="block mb-2 text-sm font-semibold"
              style="color: var(--ui-text-highlighted);"
            >
              Current Expiry
            </label>
            <input
              v-model="form.current_expiry"
              type="datetime-local"
              class="w-full px-3 py-2 text-sm rounded-lg"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
            />
          </div>
          <div>
            <label
              class="block mb-2 text-sm font-semibold"
              style="color: var(--ui-text-highlighted);"
            >
              New Expiry
            </label>
            <input
              v-model="form.new_expiry"
              type="datetime-local"
              class="w-full px-3 py-2 text-sm rounded-lg"
              style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
            />
          </div>
        </div>

        <!-- Total -->
        <div>
          <label
            class="block mb-2 text-sm font-semibold"
            style="color: var(--ui-text-highlighted);"
          >
            Total (Rp)
          </label>
          <input
            v-model.number="form.total"
            type="number"
            step="0.01"
            min="0"
            placeholder="Masukkan total"
            class="w-full px-3 py-2 text-sm rounded-lg"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
          />
        </div>

        <!-- Error -->
        <div
          v-if="serverError"
          class="px-4 py-3 rounded-lg text-sm"
          style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #ef4444;"
        >
          {{ serverError }}
        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-3 pt-2">
          <UButton
            type="submit"
            :loading="saving"
            :disabled="saving"
            color="primary"
            icon="i-heroicons-check-circle"
            :label="saving ? 'Updating...' : 'Update Invoice'"
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
        <div class="text-sm" style="color: var(--ui-text-dimmed);">
          <p class="font-semibold mb-1" style="color: var(--ui-text);">Note:</p>
          <p>
            Pastikan tanggal expiry baru sesuai dengan periode yang diinginkan. Perubahan akan langsung tersimpan di sistem.
          </p>
        </div>
      </div>
    </UCard>
  </div>
</template>

<style scoped>
input:focus {
  outline: none;
  border-color: var(--ui-primary);
}
</style>
