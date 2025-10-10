<!-- pages/roles/index.vue -->
<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useRoles } from '~/composables/useRoles'

const router = useRouter()
const { roles, fetchRoles, deleteRole } = useRoles()

const search = ref('')
const hover = ref<number | null>(null)
const isDeleteModalOpen = ref(false)
const selectedRole = ref<any | null>(null)
const errorMessage = ref<string | null>(null)
const showErrorModal = ref(false)

const filteredRoles = computed(() => {
  if (!search.value) return roles.value
  const keyword = search.value.toLowerCase()
  return roles.value.filter(r =>
    r.name?.toLowerCase().includes(keyword) ||
    r.organization?.name?.toLowerCase().includes(keyword)
  )
})

const openDeleteModal = (role: any) => {
  selectedRole.value = role
  isDeleteModalOpen.value = true
}

const confirmDelete = async () => {
  if (!selectedRole.value?.id) return
  try {
    await deleteRole(selectedRole.value.id)
    isDeleteModalOpen.value = false
    selectedRole.value = null
    await fetchRoles()
  } catch (err: any) {
    errorMessage.value = err.message || 'Gagal menghapus role'
    showErrorModal.value = true
  }
}

onMounted(() => fetchRoles())
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
        <table class="min-w-full table-auto border-collapse">
          <thead style="background: var(--ui-bg-muted); border-bottom: 1px solid var(--ui-border);">
            <tr>
              <th class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap" style="color: var(--ui-text-highlighted);">ID</th>
              <th class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap" style="color: var(--ui-text-highlighted);">Organization</th>
              <th class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap" style="color: var(--ui-text-highlighted);">Role Name</th>
              <th class="px-3 py-3 text-center text-xs font-semibold uppercase whitespace-nowrap" style="color: var(--ui-text-highlighted);">Action</th>
            </tr>
          </thead>

          <tbody style="background: var(--ui-bg);">
            <tr
              v-for="role in filteredRoles"
              :key="role.id"
              class="transition-colors"
              @mouseover="hover = role.id"
              @mouseleave="hover = null"
              :class="{ 'hovered-row': hover === role.id }"
              :style="{ borderBottom: '1px solid var(--ui-border)' }"
            >
              <td class="px-3 py-3 text-sm whitespace-nowrap">{{ role.id }}</td>
              <td class="px-3 py-3 text-sm whitespace-nowrap text-blue-400 font-medium">{{ role.organization?.name || '-' }}</td>
              <td class="px-3 py-3 text-sm whitespace-nowrap text-green-400 font-medium">{{ role.name || '-' }}</td>
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
            <tr v-if="!filteredRoles.length">
              <td colspan="4" class="text-center py-6 text-gray-400">No data found.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </UCard>

    <!-- Delete Modal -->
    <Teleport to="body">
      <div v-if="isDeleteModalOpen" class="fixed inset-0 z-[99999] flex items-center justify-center" style="background: rgba(0,0,0,0.5);">
        <UCard class="max-w-md w-full mx-4" style="background: var(--ui-bg); color: var(--ui-text); border: 1px solid var(--ui-border);">
          <template #header>
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-semibold" style="color: var(--ui-text-highlighted);">Konfirmasi Hapus</h3>
              <UButton color="gray" variant="ghost" icon="i-heroicons-x-mark-20-solid" @click="isDeleteModalOpen = false" />
            </div>
          </template>

          <div class="py-4">
            <p style="color: var(--ui-text);">
              Yakin ingin menghapus role <strong>{{ selectedRole?.name }}</strong> dari organisasi <strong>{{ selectedRole?.organization?.name }}</strong>?
            </p>
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

    <!-- Error Modal -->
    <Teleport to="body">
      <div v-if="showErrorModal" class="fixed inset-0 z-[99999] flex items-center justify-center" style="background: rgba(0,0,0,0.5);">
        <UCard class="max-w-md w-full mx-4" style="background: var(--ui-bg); color: var(--ui-text); border: 1px solid var(--ui-border);">
          <template #header>
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-semibold" style="color: var(--ui-text-highlighted);">Error</h3>
              <UButton color="gray" variant="ghost" icon="i-heroicons-x-mark-20-solid" @click="showErrorModal = false" />
            </div>
          </template>

          <div class="py-4">
            <p style="color: var(--ui-text);">{{ errorMessage }}</p>
          </div>

          <template #footer>
            <div class="flex justify-end gap-3">
              <UButton color="gray" variant="soft" label="Tutup" @click="showErrorModal = false" />
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
