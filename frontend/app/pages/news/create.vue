<script setup lang="ts">
import { ref } from 'vue'
import { useNews } from '~/composables/useNews'
import { useRouter } from '#app'
import NewsForm from '~/components/NewsForm.vue'

const router = useRouter()
const { createNews } = useNews()

const form = ref({
  date_post: '',
  title: '',
  content: '',
  thumbnail: '',
  image: '',
  status: 1
})

const submit = async () => {
  await createNews(form.value)
  router.push('/news')
}
</script>

<template>
  <div class="p-6 max-w-lg mx-auto">
    <h1 class="text-xl font-bold mb-4">Tambah Berita</h1>
    <NewsForm v-model:modelValue="form" @submit="submit" />
  </div>
</template>
