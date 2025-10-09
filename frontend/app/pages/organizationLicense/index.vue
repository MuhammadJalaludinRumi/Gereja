<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useOrganizationLicenses } from '~/composables/useOrganizationLicenses'

const { getAll, remove } = useOrganizationLicenses()
const licenses = ref<any[]>([])

const search = ref('')
const showDeleteModal = ref(false)
const deleteTarget = ref<any>(null)

const fetchData = async () => {
  licenses.value = await getAll()
}

const filteredLicenses = computed(() => {
  if (!search.value) return licenses.value
  const keyword = search.value.toLowerCase()
  return licenses.value.filter(l =>
    l.organization?.name?.toLowerCase().includes(keyword) ||
    l.license?.name?.toLowerCase().includes(keyword) ||
    String(l.id).includes(keyword)
  )
})

const openDeleteModal = (item: any) => {
  deleteTarget.value = item
  showDeleteModal.value = true
}

const confirmDelete = async () => {
  if (!deleteTarget.value) return
  await remove(deleteTarget.value.id)
  showDeleteModal.value = false
  await fetchData()
}

onMounted(fetchData)
</script>

<template>
  <div
    class="p-6 w-full overflow-hidden"
    style="background: var(--ui-bg); color: var(--ui-text);"
  >
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold" style="color: var(--ui-text-highlighted);">
        Organization Licenses
      </h1>
      <UButton
        to="/organizationLicense/create"
        icon="i-heroicons-plus-circle"
        color="primary"
        label="Tambah"
      />
    </div>

    <!-- Search -->
    <UCard class="mb-6" :ui="{ body: { padding: 'p-4' } }">
      <input
        v-model="search"
        placeholder="Cari organization / license..."
        class="w-full border rounded-lg px-3 py-2 text-sm"
        style="background: var(--ui-bg); border-color: var(--ui-border); color: var(--ui-text);"
      />
    </UCard>

    <!-- Table -->
    <UCard :ui="{ body: { padding: '' } }" class="relative overflow-hidden">
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
                class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap"
                style="color: var(--ui-text-highlighted);"
              >
                ID
              </th>
              <th
                class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap"
                style="color: var(--ui-text-highlighted);"
              >
                Organization
              </th>
              <th
                class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap"
                style="color: var(--ui-text-highlighted);"
              >
                License
              </th>
              <th
                class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap"
                style="color: var(--ui-text-highlighted);"
              >
                Max Member
              </th>
              <th
                class="px-3 py-3 text-center text-xs font-semibold uppercase whitespace-nowrap"
                style="color: var(--ui-text-highlighted);"
              >
                Active
              </th>
              <th
                class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap"
                style="color: var(--ui-text-highlighted);"
              >
                Expiry
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
              v-for="l in filteredLicenses"
              :key="l.id"
              class="transition-colors"
              :style="{ borderBottom: '1px solid var(--ui-border)' }"
            >
              <td class="px-3 py-3 text-sm whitespace-nowrap">{{ l.id }}</td>
              <td
                class="px-3 py-3 text-sm whitespace-nowrap text-blue-400 font-medium"
              >
                {{ l.organization?.name || '-' }}
              </td>
              <td
                class="px-3 py-3 text-sm whitespace-nowrap text-green-400 font-medium"
              >
                {{ l.license?.name || '-' }}
              </td>
              <td class="px-3 py-3 text-sm whitespace-nowrap">{{ l.max_member }}</td>
              <td class="px-3 py-3 text-center whitespace-nowrap">
                <span
                  class="px-2 py-1 rounded text-xs font-semibold"
                  :class="l.is_active ? 'bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-200' : 'bg-red-100 text-red-700 dark:bg-red-800 dark:text-red-200'"
                >
                  {{ l.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-3 py-3 text-sm whitespace-nowrap">{{ l.expiry }}</td>
              <td
                class="px-3 py-3 text-center whitespace-nowrap flex justify-center gap-2"
              >
                <UButton
                  :to="`/organizationLicense/${l.id}`"
                  color="yellow"
                  variant="soft"
                  size="xs"
                  icon="i-heroicons-pencil"
                  label="Edit"
                />
                <UButton
                  color="red"
                  variant="soft"
                  size="xs"
                  icon="i-heroicons-trash"
                  label="Hapus"
                  @click="openDeleteModal(l)"
                />
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div
        v-if="!filteredLicenses.length"
        class="text-center py-6 text-gray-400"
      >
        No data found.
      </div>
    </UCard>

    <!-- Delete Modal -->
    <Teleport to="body">
      <div
        v-if="showDeleteModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
      >
        <div
          class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-96"
        >
          <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">
            Konfirmasi Hapus
          </h2>
          <p class="text-gray-800 dark:text-gray-300 mb-4">
            Yakin mau hapus license
            <strong>{{ deleteTarget?.license?.name }}</strong>
            dari organization
            <strong>{{ deleteTarget?.organization?.name }}</strong>?
          </p>
          <div class="flex justify-end">
            <UButton
              color="gray"
              label="Batal"
              @click="showDeleteModal = false"
              class="mr-2"
            />
            <UButton color="red" label="Hapus" @click="confirmDelete" />
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>

<style scoped>
tr:hover {
  background: var(--ui-bg-muted) !important;
  transition: background 0.2s ease;
}
</style>
