<script setup lang="ts">
import { onMounted } from 'vue'
import { useNews } from '~/composables/useNews'

const { news, fetchNews, deleteNews } = useNews()

const handleDelete = async (id: number) => {
  if (!confirm('Apakah kamu yakin ingin menghapus berita ini?')) return
  await deleteNews(id)
}

onMounted(fetchNews)

const formatDate = (date: string) => (date ? date.split('T')[0] : '-')
</script>

<template>
  <div
    class="p-6 w-full overflow-hidden"
    style="color: var(--ui-text); background: var(--ui-bg);"
  >
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold" style="color: var(--ui-text-highlighted);">
        Daftar Berita
      </h1>
      <UButton
        to="/news/create"
        icon="i-heroicons-plus-circle"
        size="md"
        color="primary"
        label="Tambah Berita"
      />
    </div>

    <!-- Table -->
    <UCard :ui="{ body: { padding: '' } }" class="relative z-0 overflow-hidden">
      <div class="overflow-x-auto w-full">
        <table class="min-w-full table-auto border-collapse">
          <thead>
            <tr>
              <th
                class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap"
              >
                ID
              </th>
              <th
                class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap"
              >
                Tanggal
              </th>
              <th
                class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap"
              >
                Judul
              </th>
              <th
                class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap"
              >
                Status
              </th>
              <th
                class="px-3 py-3 text-center text-xs font-semibold uppercase whitespace-nowrap"
              >
                Aksi
              </th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="item in news"
              :key="item.id"
              class="transition-colors hovered-row"
            >
              <td class="px-3 py-3 text-sm font-medium whitespace-nowrap">
                {{ item.id }}
              </td>
              <td class="px-3 py-3 text-sm whitespace-nowrap">
                {{ formatDate(item.date_post) }}
              </td>
              <td class="px-3 py-3 text-sm">
                {{ item.title }}
              </td>
              <td class="px-3 py-3 text-sm whitespace-nowrap">
                <span :class="item.status ? 'text-green-600' : 'text-red-600'">
                  {{ item.status ? 'Aktif' : 'Nonaktif' }}
                </span>
              </td>
              <td class="px-3 py-3 text-sm whitespace-nowrap text-center">
                <div class="flex justify-center gap-2">
                  <UButton
                    :to="`/news/${item.id}`"
                    icon="i-heroicons-pencil-square"
                    size="xs"
                    color="blue"
                    variant="soft"
                    label="Edit"
                  />
                  <UButton
                    @click.stop="handleDelete(item.id!)"
                    icon="i-heroicons-trash"
                    size="xs"
                    color="red"
                    variant="soft"
                    label="Delete"
                  />
                </div>
              </td>
            </tr>

            <tr v-if="!news.length">
              <td
                colspan="5"
                class="px-3 py-4 text-center text-gray-500 text-sm italic"
              >
                Tidak ada berita ditemukan.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </UCard>
  </div>
</template>

<style scoped>
.hovered-row:hover {
  background-color: rgba(0, 0, 0, 0.03);
}
</style>
