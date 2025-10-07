<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useNews, type News } from '~/composables/useNews'
import { useRoute, useRouter, useRuntimeConfig } from '#app'
import NewsForm from '~/components/NewsForm.vue'

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

const fetchNews = async () => {
  const { data, error } = await useFetch<News>(`${config.public.apiBase}/news/${route.params.id}`)
  if (error.value) {
    alert('Berita tidak ditemukan!')
    router.push('/news')
    return
  }
  if (data.value) {
    form.value = {
      date_post: data.value.date_post.replace(' ', 'T'),
      title: data.value.title,
      content: data.value.content,
      thumbnail: data.value.thumbnail,
      image: data.value.image,
      status: data.value.status
    }
  }
  loading.value = false
}

onMounted(fetchNews)

const submit = async () => {
  await updateNews(Number(route.params.id), form.value)
  alert('Berita berhasil diupdate!')
  router.push('/news')
}
</script>

<template>
  <div class="p-6 max-w-lg mx-auto">
    <h1 class="text-xl font-bold mb-4">Edit Berita #{{ route.params.id }}</h1>
    <div v-if="loading">Loading...</div>
    <div v-else>
      <NewsForm v-model:modelValue="form" @submit="submit" />
    </div>
  </div>
</template>
