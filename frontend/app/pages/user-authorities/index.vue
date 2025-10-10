<!-- pages/user-authorities/index.vue -->
<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useUserAuthorities } from '~/composables/useUserAuthorities'

const { userAuthorities, loading, error, fetchAll, remove } = useUserAuthorities()

const search = ref('')
const hover = ref<number | null>(null)
const isDeleteModalOpen = ref(false)
const selectedAuthority = ref<any | null>(null)
const errorMessage = ref<string | null>(null)
const showErrorModal = ref(false)

const filteredAuthorities = computed(() => {
  if (!search.value) return userAuthorities.value
  const keyword = search.value.toLowerCase()
  return userAuthorities.value.filter(ua =>
    ua.user?.username?.toLowerCase().includes(keyword) ||
    ua.role?.name?.toLowerCase().includes(keyword)
  )
})

const openDeleteModal = (ua: any) => {
  selectedAuthority.value = ua
  isDeleteModalOpen.value = true
}

const confirmDelete = async () => {
  if (!selectedAuthority.value?.id) return
  try {
    await remove(selectedAuthority.value.id)
    isDeleteModalOpen.value = false
    selectedAuthority.value = null
    await fetchAll()
  } catch (err: any) {
    errorMessage.value = err.message || 'Gagal menghapus authority'
    showErrorModal.value = true
  }
}

onMounted(() => fetchAll())
</script>

<template>
  <div class="p-6 w-full overflow-hidden" style="background: var(--ui-bg); color: var(--ui-text);">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold" style="color: var(--ui-text-highlighted);">
        User Authorities
      </h1>
      <UButton
        to="/user-authorities/create"
        icon="i-heroicons-plus-circle"
        color="primary"
        label="Tambah"
      />
    </div>

    <!-- Search -->
    <UCard class="mb-6" :ui="{ body: { padding: 'p-4' } }">
      <input
        v-model="search"
        placeholder="Cari user / role..."
        class="w-full border rounded-lg px-3 py-2 text-sm"
        style="background: var(--ui-bg); border-color: var(--ui-border); color: var(--ui-text);"
      />
    </UCard>

    <!-- Loading -->
    <div v-if="loading" class="mb-4 text-sm text-gray-400">Loading...</div>

    <!-- Table -->
    <UCard :ui="{ body: { padding: '' } }" class="relative overflow-hidden">
      <div class="overflow-x-auto w-full">
        <table class="min-w-full table-auto border-collapse">
          <thead style="background: var(--ui-bg-muted); border-bottom: 1px solid var(--ui-border);">
            <tr>
              <th class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap" style="color: var(--ui-text-highlighted);">ID</th>
              <th class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap" style="color: var(--ui-text-highlighted);">User</th>
              <th class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap" style="color: var(--ui-text-highlighted);">Role</th>
              <th class="px-3 py-3 text-center text-xs font-semibold uppercase whitespace-nowrap" style="color: var(--ui-text-highlighted);">Action</th>
            </tr>
          </thead>

          <tbody style="background: var(--ui-bg);">
            <tr
              v-for="ua in filteredAuthorities"
              :key="ua.id"
              class="transition-colors"
              @mouseover="hover = ua.id"
              @mouseleave="hover = null"
              :class="{ 'hovered-row': hover === ua.id }"
              :style="{ borderBottom: '1px solid var(--ui-border)' }"
            >
              <td class="px-3 py-3 text-sm whitespace-nowrap">{{ ua.id }}</td>
              <td class="px-3 py-3 text-sm text-blue-400 font-medium whitespace-nowrap">{{ ua.user?.username || '-' }}</td>
              <td class="px-3 py-3 text-sm text-green-400 font-medium whitespace-nowrap">{{ ua.role?.name || '-' }}</td>
              <td class="px-3 py-3 text-center whitespace-nowrap flex justify-center gap-2">
                <UButton
                  :to="`/user-authorities/${ua.id}`"
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
                  @click="openDeleteModal(ua)"
                />
              </td>
            </tr>
            <tr v-if="!filteredAuthorities.length && !loading">
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
              Yakin ingin menghapus <strong>{{ selectedAuthority?.user?.username }}</strong> dengan role <strong>{{ selectedAuthority?.role?.name }}</strong>?
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
