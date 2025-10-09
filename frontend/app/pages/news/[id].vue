<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter, useRuntimeConfig } from '#app'
import { useNews, type News } from '~/composables/useNews'

const route = useRoute()
const router = useRouter()
const config = useRuntimeConfig()
const { updateNews } = useNews()

const form = ref<News>({
  date_post: '',
  title: '',
  content: '',
  thumbnail: '',
  image: '',
  status: 1
})

const loading = ref(true)

// 🔧 Fungsi untuk mengubah format tanggal agar cocok dengan input type="datetime-local"
function formatDateForInput(dateString: string) {
  if (!dateString) return ''
  // Contoh: "2025-10-09 14:30:00" → "2025-10-09T14:30"
  return dateString.replace(' ', 'T').slice(0, 16)
}

const fetchNews = async () => {
  try {
    const { data, error } = await useFetch<News>(`${config.public.apiBase}/news/${route.params.id}`)
    if (error.value) {
      console.error(error.value)
      router.push('/news')
      return
    }
    if (data.value) {
      form.value = {
        id: data.value.id,
        date_post: formatDateForInput(data.value.date_post),
        title: data.value.title,
        content: data.value.content,
        thumbnail: data.value.thumbnail,
        image: data.value.image,
        status: data.value.status
      }
    }
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

onMounted(fetchNews)

const submit = async () => {
  await updateNews(Number(route.params.id), {
    ...form.value,
    // Convert balik ke format database (tanpa "T")
    date_post: form.value.date_post.replace('T', ' ')
  })
  router.push('/news')
}
</script>

<template>
  <div class="p-4 max-w-2xl mx-auto">
    <UCard class="p-6 rounded-2xl shadow-sm bg-[var(--ui-bg)] text-[var(--ui-text)]">
      <template #header>
        <h1 class="text-lg font-semibold">✏️ Edit Berita #{{ route.params.id }}</h1>
      </template>

      <div v-if="loading" class="py-8 text-center text-gray-500">Memuat data...</div>

      <form v-else @submit.prevent="submit" class="space-y-4">
        <UInput v-model="form.title" label="Judul Berita" placeholder="Masukkan judul berita" required />
        <UInput v-model="form.date_post" label="Tanggal Posting" type="datetime-local" required />
        <UInput v-model="form.content" label="Konten" type="textarea" placeholder="Isi berita..." required />
        <UInput v-model="form.thumbnail" label="URL Thumbnail" placeholder="https://..." />
        <UInput v-model="form.image" label="URL Gambar Utama" placeholder="https://..." />

        <div class="flex justify-end gap-2 pt-4">
          <UButton color="gray" variant="soft" @click="router.push('/news')">Batal</UButton>
          <UButton type="submit" color="primary">Perbarui</UButton>
        </div>
      </form>
    </UCard>
  </div>
</template>
