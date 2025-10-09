<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRoles } from '~/composables/useRoles'

const { roles, fetchRoles, deleteRole } = useRoles()
const router = useRouter()

const search = ref('')
const showDeleteModal = ref(false)
const deleteTarget = ref<any>(null)

const filteredRoles = computed(() => {
  if (!search.value) return roles.value
  const keyword = search.value.toLowerCase()
  return roles.value.filter(r =>
    r.name?.toLowerCase().includes(keyword) ||
    r.organization?.name?.toLowerCase().includes(keyword)
  )
})

const openDeleteModal = (role: any) => {
  deleteTarget.value = role
  showDeleteModal.value = true
}

const confirmDelete = async () => {
  if (!deleteTarget.value) return
  await deleteRole(deleteTarget.value.id)
  showDeleteModal.value = false
  await fetchRoles()
}

onMounted(() => {
  fetchRoles()
})
</script>

<template>
  <div class="p-6 w-full overflow-hidden" style="background: var(--ui-bg); color: var(--ui-text);">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold" style="color: var(--ui-text-highlighted);">Roles</h1>
      <UButton
        to="/roles/create"
        icon="i-heroicons-plus-circle"
        color="primary"
        label="Tambah"
      />
    </div>

    <!-- Search -->
    <UCard class="mb-6" :ui="{ body: { padding: 'p-4' } }">
      <input
        v-model="search"
        placeholder="Cari role / organisasi..."
        class="w-full border rounded-lg px-3 py-2 text-sm"
        style="background: var(--ui-bg); border-color: var(--ui-border); color: var(--ui-text);"
      />
    </UCard>

    <!-- Table -->
    <UCard :ui="{ body: { padding: '' } }" class="relative overflow-hidden">
      <div class="overflow-x-auto w-full">
        <table class="min-w-full table-auto border-collapse" style="color: var(--ui-text);">
          <thead style="background: var(--ui-bg-muted); border-bottom: 1px solid var(--ui-border);">
            <tr>
              <th class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap"
                style="color: var(--ui-text-highlighted);">ID</th>
              <th class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap"
                style="color: var(--ui-text-highlighted);">Organization</th>
              <th class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap"
                style="color: var(--ui-text-highlighted);">Role Name</th>
              <th class="px-3 py-3 text-center text-xs font-semibold uppercase whitespace-nowrap"
                style="color: var(--ui-text-highlighted);">Action</th>
            </tr>
          </thead>

          <tbody style="background: var(--ui-bg);">
            <tr
              v-for="role in filteredRoles"
              :key="role.id"
              class="transition-colors"
              :style="{ borderBottom: '1px solid var(--ui-border)' }"
            >
              <td class="px-3 py-3 text-sm whitespace-nowrap">{{ role.id }}</td>
              <td class="px-3 py-3 text-sm whitespace-nowrap text-blue-400 font-medium">
                {{ role.organization?.name || '-' }}
              </td>
              <td class="px-3 py-3 text-sm whitespace-nowrap text-green-400 font-medium">
                {{ role.name || '-' }}
              </td>
              <td class="px-3 py-3 text-center whitespace-nowrap flex justify-center gap-2">
                <UButton
                  :to="`/roles/${role.id}`"
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
                  @click="openDeleteModal(role)"
                />
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="!filteredRoles.length" class="text-center py-6 text-gray-400">
        No data found.
      </div>
    </UCard>

    <!-- Modal Delete -->
    <Teleport to="body">
      <div
        v-if="showDeleteModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
      >
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-96">
          <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Konfirmasi Hapus</h2>
          <p class="text-gray-800 dark:text-gray-300 mb-4">
            Yakin mau hapus role <strong>{{ deleteTarget?.name }}</strong> dari organisasi
            <strong>{{ deleteTarget?.organization?.name }}</strong>?
          </p>
          <div class="flex justify-end">
            <UButton color="gray" label="Batal" @click="showDeleteModal = false" class="mr-2" />
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
