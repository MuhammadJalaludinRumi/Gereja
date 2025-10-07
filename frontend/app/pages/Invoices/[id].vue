<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useInvoices, type Invoice } from '~/composables/useInvoices'
import { useRoute, useRouter, useRuntimeConfig } from '#app'
import InvoiceForm from '~/components/InvoiceForm.vue'

const route = useRoute()
const router = useRouter()
const config = useRuntimeConfig()
const { updateInvoice } = useInvoices()

// Data form
const form = ref<Invoice>({
  organization_id: 0,
  date: '',
  current_expiry: '',
  new_expiry: '',
  total: 0
})

const loading = ref(true)

// Ambil data invoice dari backend berdasarkan ID
const fetchInvoice = async () => {
  try {
    const { data, error } = await useFetch<Invoice>(
      `${config.public.apiBase}/invoices/${route.params.id}`
    )

    if (error.value) {
      alert('Data invoice tidak ditemukan!')
      router.push('/invoices')
      return
    }

    if (data.value) {
      // isi form dengan data lama
      form.value = {
        organization_id: data.value.organization_id,
        date: data.value.date,
        current_expiry: data.value.current_expiry
          ? data.value.current_expiry.replace(' ', 'T') // biar cocok sama input datetime-local
          : '',
        new_expiry: data.value.new_expiry
          ? data.value.new_expiry.replace(' ', 'T')
          : '',
        total: data.value.total
      }
    }
  } catch (e) {
    console.error(e)
    alert('Gagal mengambil data invoice!')
  } finally {
    loading.value = false
  }
}

onMounted(fetchInvoice)

// Submit update
const submit = async () => {
  try {
    await updateInvoice(Number(route.params.id), form.value)
    alert('Invoice berhasil diperbarui!')
    router.push('/invoices')
  } catch (e) {
    console.error(e)
    alert('Gagal memperbarui invoice!')
  }
}
</script>

<template>
  <div class="p-6 max-w-md mx-auto">
    <h1 class="text-xl font-bold mb-4">Edit Invoice #{{ route.params.id }}</h1>

    <div v-if="loading" class="text-gray-500">Loading data invoice...</div>

    <div v-else>
      <InvoiceForm
        v-model:modelValue="form"
        @submit="submit"
      />
    </div>
  </div>
</template>
