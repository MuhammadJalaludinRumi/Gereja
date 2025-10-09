<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useInvoices } from '~/composables/useInvoices'

const { invoices, fetchInvoices, deleteInvoice } = useInvoices()
const search = ref('')
const hover = ref<number | null>(null)
const showDeleteModal = ref(false)
const deleteTarget = ref<any>(null)

onMounted(fetchInvoices)

const tableHeaders = ['ID', 'Organization', 'Date', 'Current Expiry', 'New Expiry', 'Total']

const filteredInvoices = computed(() => {
  if (!search.value) return invoices.value
  const keyword = search.value.toLowerCase()
  return invoices.value.filter((inv: any) =>
    inv.id?.toString().includes(keyword) ||
    inv.organization_id?.toString().includes(keyword) ||
    inv.total?.toString().includes(keyword)
  )
})

const formatDate = (value: string | null) => {
  if (!value) return '-'
  return value.split('T')[0]
}

const formatCurrency = (value: number | null) => {
  if (!value) return 'Rp 0'
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(value)
}

const openDeleteModal = (invoice: any) => {
  deleteTarget.value = invoice
  showDeleteModal.value = true
}

const confirmDelete = async () => {
  if (!deleteTarget.value) return
  await deleteInvoice(deleteTarget.value.id!)
  showDeleteModal.value = false
  deleteTarget.value = null
}
</script>

<template>
  <div
    class="p-6 w-full overflow-hidden"
    style="color: var(--ui-text); background: var(--ui-bg);"
  >
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1
        class="text-2xl font-bold"
        style="color: var(--ui-text-highlighted);"
      >
        Invoices
      </h1>
      <UButton
        to="/invoices/create"
        icon="i-heroicons-plus-circle"
        size="md"
        color="primary"
        label="Tambah Invoice"
      />
    </div>

    <!-- Search -->
    <div class="mb-4">
      <UInput
        v-model="search"
        placeholder="Cari invoice..."
        icon="i-heroicons-magnifying-glass"
        class="w-80"
      />
    </div>

    <!-- Table Card -->
    <UCard :ui="{ body: { padding: '' } }" class="relative z-0 overflow-hidden">
      <div class="overflow-x-auto w-full">
        <table
          class="min-w-full table-auto border-collapse"
          style="color: var(--ui-text);"
        >
          <thead
            style="background: var(--ui-bg-muted); border-bottom: 1px solid var(--ui-border);"
          >
            <tr>
              <th
                v-for="head in tableHeaders"
                :key="head"
                class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap"
                style="color: var(--ui-text-highlighted);"
              >
                {{ head }}
              </th>
              <th
                class="px-3 py-3 text-center text-xs font-semibold uppercase whitespace-nowrap"
                style="color: var(--ui-text-highlighted);"
              >
                Action
              </th>
            </tr>
          </thead>

          <tbody style="background: var(--ui-bg);">
            <tr
              v-for="inv in filteredInvoices"
              :key="inv.id"
              class="transition-colors"
              :style="{
                borderBottom: '1px solid var(--ui-border)',
                background: 'var(--ui-bg)',
              }"
              @mouseover="hover = inv.id"
              @mouseleave="hover = null"
              :class="{ 'hovered-row': hover === inv.id }"
            >
              <td class="px-3 py-3 text-sm font-medium whitespace-nowrap" style="color: var(--ui-text-highlighted);">
                #{{ inv.id }}
              </td>
              <td class="px-3 py-3 text-sm whitespace-nowrap">
                <UBadge color="blue" variant="soft">
                  {{ inv.organization?.name }}
                </UBadge>
              </td>
              <td class="px-3 py-3 text-sm whitespace-nowrap">
                {{ formatDate(inv.date) }}
              </td>
              <td class="px-3 py-3 text-sm whitespace-nowrap">
                {{ formatDate(inv.current_expiry) }}
              </td>
              <td class="px-3 py-3 text-sm whitespace-nowrap">
                {{ formatDate(inv.new_expiry) }}
              </td>
              <td class="px-3 py-3 text-sm font-semibold whitespace-nowrap" style="color: var(--ui-text-highlighted);">
                {{ formatCurrency(inv.total) }}
              </td>
              <td class="px-3 py-3 text-center whitespace-nowrap">
                <div class="flex justify-center gap-2">
                  <UButton
                    :to="`/invoices/${inv.id}`"
                    icon="i-heroicons-pencil-square"
                    size="xs"
                    color="blue"
                    variant="soft"
                    label="Edit"
                  />
                  <UButton
                    @click.stop="openDeleteModal(inv)"
                    icon="i-heroicons-trash"
                    size="xs"
                    color="red"
                    variant="soft"
                    label="Hapus"
                  />
                </div>
              </td>
            </tr>
            <tr v-if="!filteredInvoices.length">
              <td colspan="7" class="p-4 text-center text-gray-500">
                Data tidak ditemukan
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </UCard>

    <!-- Delete Modal -->
    <Teleport to="body">
      <div
        v-if="showDeleteModal"
        class="fixed inset-0 z-[99999] flex items-center justify-center"
        style="background: rgba(0, 0, 0, 0.5);"
      >
        <UCard
          class="max-w-md w-full mx-4"
          style="background: var(--ui-bg); color: var(--ui-text); border: 1px solid var(--ui-border);"
        >
          <template #header>
            <div class="flex items-center justify-between">
              <h3
                class="text-lg font-semibold"
                style="color: var(--ui-text-highlighted);"
              >
                Konfirmasi Hapus
              </h3>
              <UButton
                color="gray"
                variant="ghost"
                icon="i-heroicons-x-mark-20-solid"
                @click="showDeleteModal = false"
              />
            </div>
          </template>

          <div class="py-4">
            <p style="color: var(--ui-text);">
              Yakin ingin menghapus invoice <strong>#{{ deleteTarget?.id }}</strong> dengan total
              <strong>{{ formatCurrency(deleteTarget?.total) }}</strong>?
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
.hovered-row {
  background: var(--ui-bg-muted) !important;
}
</style>
