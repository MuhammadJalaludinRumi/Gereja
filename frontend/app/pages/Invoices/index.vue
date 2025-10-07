<script setup lang="ts">
import { useInvoices } from '~/composables/useInvoices'

const { invoices, fetchInvoices, deleteInvoice } = useInvoices()
onMounted(fetchInvoices)

const remove = async (id: number) => {
  if (confirm('Yakin mau hapus?')) await deleteInvoice(id)
}
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Invoices</h1>
    <NuxtLink to="/invoices/create" class="bg-blue-600 text-white px-4 py-2 rounded">Tambah</NuxtLink>
    <table class="mt-4 w-full border">
      <thead>
        <tr class="bg-gray-100">
          <th>ID</th>
          <th>Org ID</th>
          <th>Date</th>
          <th>Current Expiry</th>
          <th>New Expiry</th>
          <th>Total</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="inv in invoices" :key="inv.id" class="border-t">
          <td>{{ inv.id }}</td>
          <td>{{ inv.organization_id }}</td>
          <td>{{ inv.date }}</td>
          <td>{{ inv.current_expiry }}</td>
          <td>{{ inv.new_expiry }}</td>
          <td>{{ inv.total }}</td>
          <td>
            <NuxtLink :to="`/invoices/${inv.id}`" class="text-blue-500">Edit</NuxtLink> |
            <button @click="remove(inv.id!)" class="text-red-500">Hapus</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
