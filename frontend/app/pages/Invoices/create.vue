<script setup lang="ts">
import { ref } from 'vue'
import { useInvoices } from '~/composables/useInvoices'
import { useRouter } from '#app'

const router = useRouter()
const { createInvoice } = useInvoices()

const form = ref({
  organization_id: 1,
  date: '',
  current_expiry: '',
  new_expiry: '',
  total: 0
})

const submit = async () => {
  await createInvoice(form.value)
  router.push('/invoices')
}
</script>

<template>
  <div class="p-6 max-w-md mx-auto">
    <h1 class="text-xl font-bold mb-4">Tambah Invoice</h1>
    <form @submit.prevent="submit" class="flex flex-col gap-3">
      <input v-model="form.organization_id" type="number" placeholder="Organization ID" class="border p-2" />
      <input v-model="form.date" type="date" placeholder="Date" class="border p-2" />
      <input v-model="form.current_expiry" type="datetime-local" placeholder="Current Expiry" class="border p-2" />
      <input v-model="form.new_expiry" type="datetime-local" placeholder="New Expiry" class="border p-2" />
      <input v-model="form.total" type="number" step="0.01" placeholder="Total" class="border p-2" />
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
    </form>
  </div>
</template>
