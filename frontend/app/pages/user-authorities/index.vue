<!-- pages/user-authorities/index.vue -->
<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useUserAuthorities } from '~/composables/useUserAuthorities'

const { userAuthorities, loading, error, fetchAll, remove } = useUserAuthorities()
const search = ref('')
const showDeleteModal = ref(false)
const deleteTarget = ref<any>(null)

const filteredAuthorities = computed(() => {
  if (!search.value) return userAuthorities.value
  const keyword = search.value.toLowerCase()
  return userAuthorities.value.filter(ua =>
    ua.user?.username?.toLowerCase().includes(keyword) ||
    ua.role?.name?.toLowerCase().includes(keyword)
  )
})

const openDeleteModal = (ua: any) => {
  deleteTarget.value = ua
  showDeleteModal.value = true
}

const confirmDelete = async () => {
  if (!deleteTarget.value) return
  await remove(deleteTarget.value.id)
  showDeleteModal.value = false
  await fetchAll()
}

onMounted(() => {
  fetchAll()
})
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

    <!-- Loading & Error -->
    <div v-if="loading" class="mb-4 text-sm text-gray-400">Loading...</div>
    <div v-if="error" class="text-red-600 mb-4">{{ error }}</div>

    <!-- Table -->
    <UCard :ui="{ body: { padding: '' } }" class="relative overflow-hidden">
      <div class="overflow-x-auto w-full">
        <table class="min-w-full table-auto border-collapse" style="color: var(--ui-text);">
          <thead style="background: var(--ui-bg-muted); border-bottom: 1px solid var(--ui-border);">
            <tr>
              <th class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap"
                style="color: var(--ui-text-highlighted);">ID</th>
              <th class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap"
                style="color: var(--ui-text-highlighted);">User</th>
              <th class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap"
                style="color: var(--ui-text-highlighted);">Role</th>
              <th class="px-3 py-3 text-center text-xs font-semibold uppercase whitespace-nowrap"
                style="color: var(--ui-text-highlighted);">Action</th>
            </tr>
          </thead>

          <tbody style="background: var(--ui-bg);">
            <tr
              v-for="ua in filteredAuthorities"
              :key="ua.id"
              class="transition-colors"
              :style="{ borderBottom: '1px solid var(--ui-border)' }"
            >
              <td class="px-3 py-3 text-sm whitespace-nowrap">{{ ua.id }}</td>
              <td class="px-3 py-3 text-sm whitespace-nowrap text-blue-400 font-medium">
                {{ ua.user?.username || '-' }}
              </td>
              <td class="px-3 py-3 text-sm whitespace-nowrap text-green-400 font-medium">
                {{ ua.role?.name || '-' }}
              </td>
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
          </tbody>
        </table>
      </div>

      <div v-if="!filteredAuthorities.length && !loading" class="text-center py-6 text-gray-400">
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
            Yakin mau hapus <strong>{{ deleteTarget?.user?.username }}</strong> dengan role
            <strong>{{ deleteTarget?.role?.name }}</strong>?
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
