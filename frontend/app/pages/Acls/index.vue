<template>
  <div class="p-6 w-full overflow-hidden" style="background: var(--ui-bg); color: var(--ui-text);">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold" style="color: var(--ui-text-highlighted);">Daftar ACL</h1>
      <UButton
        to="/acls/create"
        icon="i-heroicons-plus-circle"
        size="md"
        color="primary"
        label="Tambah ACL"
      />
    </div>

    <UCard :ui="{ body: { padding: '' } }" class="relative overflow-hidden">
      <div v-if="loading" class="p-4 text-center text-sm text-gray-500">Memuat data...</div>
      <div v-else class="overflow-x-auto w-full">
        <table class="min-w-full table-auto border-collapse">
          <thead>
            <tr>
              <th class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap">ID</th>
              <th class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap">Nama</th>
              <th class="px-3 py-3 text-center text-xs font-semibold uppercase whitespace-nowrap">Aksi</th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="item in acls"
              :key="item.id"
              class="border-t hover:bg-gray-50 transition"
            >
              <td class="px-3 py-3 text-sm font-medium">{{ item.id }}</td>
              <td class="px-3 py-3 text-sm">{{ item.name }}</td>
              <td class="px-3 py-3 text-sm text-center">
                <div class="flex justify-center gap-2">
                  <UButton
                    :to="`/acls/${item.id}`"
                    icon="i-heroicons-pencil-square"
                    size="xs"
                    color="blue"
                    variant="soft"
                    label="Edit"
                  />
                  <UButton
                    @click="hapus(item.id)"
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

<script setup lang="ts">
import { onMounted } from 'vue'
import { useAcls } from '~/composables/useAcls'

const { acls, fetchAll, remove, loading } = useAcls()

onMounted(fetchAll)

const hapus = async (id: number) => {
  if (confirm('Yakin ingin menghapus ACL ini?')) {
    await remove(id)
    await fetchAll()
  }
}
</script>
