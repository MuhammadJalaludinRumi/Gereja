<script setup lang="ts">
import { useNews } from '~/composables/useNews'

const { news, fetchNews, deleteNews } = useNews()
onMounted(fetchNews)

const remove = async (id: number) => {
  if (confirm('Yakin mau hapus berita ini?')) {
    await deleteNews(id)
  }
}
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">News</h1>
    <NuxtLink to="/news/create" class="bg-blue-600 text-white px-4 py-2 rounded">Tambah Berita</NuxtLink>

    <table class="mt-4 w-full border">
      <thead class="bg-gray-100">
        <tr>
          <th>ID</th>
          <th>Tanggal</th>
          <th>Judul</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in news" :key="item.id" class="border-t">
          <td>{{ item.id }}</td>
          <td>{{ item.date_post }}</td>
          <td>{{ item.title }}</td>
          <td>
            <span :class="item.status ? 'text-green-600' : 'text-red-600'">
              {{ item.status ? 'Aktif' : 'Nonaktif' }}
            </span>
          </td>
          <td>
            <NuxtLink :to="`/news/${item.id}`" class="text-blue-500">Edit</NuxtLink> |
            <button @click="remove(item.id!)" class="text-red-500">Hapus</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
