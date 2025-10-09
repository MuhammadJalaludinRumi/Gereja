<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useUsers } from '~/composables/useUsers'

const { users, fetchUsers, deleteUser } = useUsers()
const tableHeaders = ['ID', 'Username', 'Name', 'Active', 'Role', 'Actions']

const fetchData = async () => {
  try {
    await fetchUsers()
  } catch (error) {
    console.error('Gagal memuat data pengguna:', error)
  }
}

const handleDelete = async (id: number) => {
  if (!confirm('Apakah kamu yakin ingin menghapus user ini?')) return
  try {
    await deleteUser(id)
    await fetchData()
  } catch (error) {
    console.error('Gagal menghapus user:', error)
  }
}

onMounted(fetchData)
</script>

<template>
  <div class="p-6 w-full overflow-hidden" style="color: var(--ui-text); background: var(--ui-bg);">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold" style="color: var(--ui-text-highlighted);">
        Daftar Pengguna
      </h1>
      <UButton
        to="/users/create"
        icon="i-heroicons-plus-circle"
        size="md"
        color="primary"
        label="Tambah User"
      />
    </div>

    <!-- Table -->
    <UCard :ui="{ body: { padding: '' } }" class="relative z-0 overflow-hidden">
      <div class="overflow-x-auto w-full">
        <table class="min-w-full table-auto border-collapse">
          <thead>
            <tr>
              <th
                v-for="head in tableHeaders"
                :key="head"
                class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap"
              >
                {{ head }}
              </th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="user in users"
              :key="user.id"
              class="transition-colors hovered-row border-t"
            >
              <td class="px-3 py-3 text-sm">{{ user.id }}</td>
              <td class="px-3 py-3 text-sm">{{ user.username }}</td>
              <td class="px-3 py-3 text-sm">{{ user.name }}</td>
              <td class="px-3 py-3 text-sm">
                <span :class="user.is_active ? 'text-green-600' : 'text-red-600'">
                  {{ user.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-3 py-3 text-sm">{{ user.role_id }}</td>
              <td class="px-3 py-3 text-sm text-center">
                <div class="flex justify-center gap-2">
                  <UButton
                    :to="`/users/${user.id}`"
                    icon="i-heroicons-pencil-square"
                    size="xs"
                    color="blue"
                    variant="soft"
                    label="Edit"
                  />
                  <UButton
                    @click.stop="handleDelete(user.id)"
                    icon="i-heroicons-trash"
                    size="xs"
                    color="red"
                    variant="soft"
                    label="Delete"
                  />
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </UCard>
  </div>
</template>
