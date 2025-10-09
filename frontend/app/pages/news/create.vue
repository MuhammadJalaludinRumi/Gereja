<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from '#app'
import { useNews } from '~/composables/useNews'

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
  <div class="p-4 max-w-2xl mx-auto">
    <UCard class="p-6 rounded-2xl shadow-sm bg-[var(--ui-bg)] text-[var(--ui-text)]">
      <h1 class="text-lg font-semibold mb-4">📰 Tambah Berita</h1>

      <form @submit.prevent="submit" class="space-y-4">
        <UInput v-model="form.title" label="Judul Berita" placeholder="Masukkan judul berita" required />
        <UInput v-model="form.date_post" label="Tanggal Posting" type="datetime-local" required />
        <UInput v-model="form.content" label="Konten" type="textarea" placeholder="Isi berita..." required />
        <UInput v-model="form.thumbnail" label="URL Thumbnail" placeholder="https://..." />
        <UInput v-model="form.image" label="URL Gambar Utama" placeholder="https://..." />

        <div class="flex justify-end gap-2 pt-4">
          <UButton color="gray" variant="soft" @click="router.push('/news')">Batal</UButton>
          <UButton type="submit" color="primary">Simpan</UButton>
        </div>
      </form>
    </UCard>
  </div>
</template>
