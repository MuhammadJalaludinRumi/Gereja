<template>
  <div class="p-6 w-full overflow-hidden" style="color: var(--ui-text); background: var(--ui-bg);">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold" style="color: var(--ui-text-highlighted);">
        Daftar Organization
      </h1>
      <UButton to="/organizations/create" icon="i-heroicons-plus-circle" size="md" color="primary"
        label="Tambah Organization" />
    </div>

    <!-- Table Card -->
    <UCard :ui="{ body: { padding: '' } }" class="relative z-0 overflow-hidden">
      <div class="overflow-x-auto w-full">
        <table class="min-w-full table-auto border-collapse" style="color: var(--ui-text);">
          <thead style="background: var(--ui-bg-muted); border-bottom: 1px solid var(--ui-border);">
            <tr>
              <th v-for="head in tableHeaders" :key="head"
                class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap"
                style="color: var(--ui-text-highlighted);">
                {{ head }}
              </th>
              <th class="px-3 py-3 text-center text-xs font-semibold uppercase whitespace-nowrap"
                style="color: var(--ui-text-highlighted);">
                Action
              </th>
            </tr>
          </thead>

          <tbody style="background: var(--ui-bg);">
            <tr v-for="org in organizations" :key="org.id" class="transition-colors" :style="{
              borderBottom: '1px solid var(--ui-border)',
              background: 'var(--ui-bg)',
            }" @mouseover="hover = org.id" @mouseleave="hover = null" :class="{ 'hovered-row': hover === org.id }">
              <td class="px-3 py-3 text-sm font-medium whitespace-nowrap" style="color: var(--ui-text-highlighted);">
                {{ org.name }}
              </td>
              <td class="px-3 py-3 text-sm whitespace-nowrap">{{ org.abbreviation }}</td>
              <td class="px-3 py-3 text-sm max-w-[200px] truncate">{{ org.address }}</td>
              <td class="px-3 py-3 text-sm">{{ org.city?.name }}</td>
              <td class="px-3 py-3 text-sm">{{ org.latitude }}</td>
              <td class="px-3 py-3 text-sm">{{ org.longitude }}</td>
              <td class="px-3 py-3 text-sm">{{ org.phone }}</td>
              <td class="px-3 py-3 text-sm">{{ org.email }}</td>
              <td class="px-3 py-3 text-sm whitespace-nowrap">
                <a v-if="org.website" :href="org.website" target="_blank"
                  style="color: var(--ui-primary); text-decoration: underline;">
                  {{ org.website }}
                </a>
              </td>
              <td class="px-3 py-3 text-sm whitespace-nowrap">
                <img v-if="org.logo" :src="org.logo" alt="Logo" class="h-10 w-auto rounded border"
                  style="border-color: var(--ui-border);" />
              </td>
              <td class="px-3 py-3 text-sm whitespace-nowrap">{{ formatDate(org.founded) }}</td>
              <td class="px-3 py-3 text-sm whitespace-nowrap">{{ org.legal }}</td>
              <td class="px-3 py-3 text-sm whitespace-nowrap">
                <div class="flex justify-center gap-2">
                  <UButton :to="`/organizations/${org.id}`" icon="i-heroicons-pencil-square" size="xs" color="blue"
                    variant="soft" label="Edit" />
                  <UButton @click.stop="openDeleteModal(org.id)" icon="i-heroicons-trash" size="xs" color="red"
                    variant="soft" label="Delete" />
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </UCard>

    <!-- Delete Modal -->
    <Teleport to="body">
      <div v-if="isDeleteModalOpen" class="fixed inset-0 z-[99999] flex items-center justify-center"
        style="background: rgba(0,0,0,0.5);">
        <UCard class="max-w-md w-full mx-4"
          style="background: var(--ui-bg); color: var(--ui-text); border: 1px solid var(--ui-border);">
          <template #header>
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-semibold" style="color: var(--ui-text-highlighted);">
                Konfirmasi Hapus
              </h3>
              <UButton color="gray" variant="ghost" icon="i-heroicons-x-mark-20-solid"
                @click="isDeleteModalOpen = false" />
            </div>
          </template>

          <div class="py-4">
            <p style="color: var(--ui-text);">Yakin ingin menghapus data ini?</p>
          </div>

          <template #footer>
            <div class="flex justify-end gap-3">
              <UButton color="gray" variant="soft" label="Batal" @click="isDeleteModalOpen = false" />
              <UButton color="red" label="Hapus" @click="confirmDelete" />
            </div>
          </template>
        </UCard>
      </div>
    </Teleport>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useOrganizations } from '~/composables/useOrganizations'

const organizations = ref<any[]>([])
const hover = ref<string | null>(null)
const { getOrganizations, deleteOrganization } = useOrganizations()
const isDeleteModalOpen = ref(false)
const selectedOrgId = ref<string>('')

const tableHeaders = [
  'Name', 'Abbreviation', 'Address', 'City',
  'Latitude', 'Longitude', 'Phone', 'Email',
  'Website', 'Logo', 'Founded', 'Legal'
]

const fetchData = async () => {
  organizations.value = await getOrganizations()
}

onMounted(fetchData)

const openDeleteModal = (id: string) => {
  selectedOrgId.value = id
  isDeleteModalOpen.value = true
}

const confirmDelete = async () => {
  if (!selectedOrgId.value) return
  await deleteOrganization(selectedOrgId.value)
  isDeleteModalOpen.value = false
  await fetchData()
}

const formatDate = (date: string) => {
  if (!date) return '-'
  return date.split(' ')[0] // ambil bagian sebelum spasi
}

</script>

<style scoped>
.hovered-row {
  background: var(--ui-bg-muted) !important;
}
</style>
