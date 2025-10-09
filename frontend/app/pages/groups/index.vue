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
        Daftar Groups
      </h1>
      <UButton
        to="/groups/create"
        icon="i-heroicons-plus-circle"
        size="md"
        color="primary"
        label="Tambah Group"
      />
    </div>

    <!-- Search -->
    <div class="mb-4">
      <UInput
        v-model="search"
        placeholder="Cari group..."
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
              v-for="g in filteredGroups"
              :key="g.id"
              class="transition-colors"
              :style="{
                borderBottom: '1px solid var(--ui-border)',
                background: 'var(--ui-bg)',
              }"
              @mouseover="hover = g.id"
              @mouseleave="hover = null"
              :class="{ 'hovered-row': hover === g.id }"
            >
              <td class="px-3 py-3 text-sm font-medium whitespace-nowrap" style="color: var(--ui-text-highlighted);">
                {{ g.name }}
              </td>
              <td class="px-3 py-3 text-sm max-w-[200px] truncate">{{ g.address }}</td>
              <td class="px-3 py-3 text-sm">{{ g.city?.name }}</td>
              <td class="px-3 py-3 text-sm">{{ g.phone }}</td>
              <td class="px-3 py-3 text-sm">{{ g.email }}</td>
              <td class="px-3 py-3 text-sm">{{ g.website }}</td>
              <td class="px-3 py-3 text-sm whitespace-nowrap">
                <img
                  v-if="g.logo"
                  :src="g.logo"
                  alt="Logo"
                  class="h-10 w-auto rounded border"
                  style="border-color: var(--ui-border);"
                />
              </td>
              <td class="px-3 py-3 text-sm whitespace-nowrap">
                {{ formatDate(g.founded) }}
              </td>
              <td class="px-3 py-3 text-center whitespace-nowrap">
                <div class="flex justify-center gap-2">
                  <UButton
                    :to="`/groups/${g.id}`"
                    icon="i-heroicons-pencil-square"
                    size="xs"
                    color="blue"
                    variant="soft"
                    label="Edit"
                  />
                  <UButton
                    @click.stop="openDeleteModal(g)"
                    icon="i-heroicons-trash"
                    size="xs"
                    color="red"
                    variant="soft"
                    label="Delete"
                  />
                </div>
              </td>
            </tr>
            <tr v-if="!filteredGroups.length">
              <td colspan="9" class="p-4 text-center text-gray-500">
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
              Yakin ingin menghapus <strong>{{ deleteTarget?.name }}</strong>?
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

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useGroups } from '~/composables/useGroups'

const { getGroups, deleteGroup } = useGroups()
const groups = ref<any[]>([])
const search = ref('')
const hover = ref<number | null>(null)
const showDeleteModal = ref(false)
const deleteTarget = ref<any>(null)

const tableHeaders = ['Name', 'Address', 'City', 'Phone', 'Email', 'Website', 'Logo', 'Founded']

const fetchGroups = async () => {
  const { data } = await getGroups()
  groups.value = data.value || []
}

onMounted(fetchGroups)

const filteredGroups = computed(() => {
  if (!search.value) return groups.value
  return groups.value.filter((g) =>
    g.name.toLowerCase().includes(search.value.toLowerCase())
  )
})

const formatDate = (value: string | null) => {
  if (!value) return '-'
  return value.split('T')[0]
}

const openDeleteModal = (group: any) => {
  deleteTarget.value = group
  showDeleteModal.value = true
}

const confirmDelete = async () => {
  if (!deleteTarget.value) return
  await deleteGroup(deleteTarget.value.id)
  groups.value = groups.value.filter((g) => g.id !== deleteTarget.value.id)
  showDeleteModal.value = false
  deleteTarget.value = null
}
</script>

<style scoped>
.hovered-row {
  background: var(--ui-bg-muted) !important;
}
</style>
