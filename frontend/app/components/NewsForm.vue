<script setup lang="ts">
import { ref, watchEffect } from 'vue'
import type { News } from '~/composables/useNews'

const props = defineProps<{ modelValue: News }>()
const emit = defineEmits(['update:modelValue', 'submit'])

const form = ref<News>({ ...props.modelValue })

watchEffect(() => {
  form.value = { ...props.modelValue }
})
watchEffect(() => {
  emit('update:modelValue', form.value)
})
</script>

<template>
  <form @submit.prevent="$emit('submit')" class="flex flex-col gap-3">
    <label>
      <span class="text-sm">Tanggal Post</span>
      <input v-model="form.date_post" type="datetime-local" class="border p-2 w-full" />
    </label>
    <label>
      <span class="text-sm">Judul</span>
      <input v-model="form.title" type="text" class="border p-2 w-full" />
    </label>
    <label>
      <span class="text-sm">Isi Berita</span>
      <textarea v-model="form.content" class="border p-2 w-full" rows="4"></textarea>
    </label>
    <label>
      <span class="text-sm">Thumbnail (URL / path)</span>
      <input v-model="form.thumbnail" type="text" class="border p-2 w-full" />
    </label>
    <label>
      <span class="text-sm">Image (URL / path)</span>
      <input v-model="form.image" type="text" class="border p-2 w-full" />
    </label>
    <label>
      <span class="text-sm">Status</span>
      <select v-model="form.status" class="border p-2 w-full">
        <option value="1">Aktif</option>
        <option value="0">Nonaktif</option>
      </select>
    </label>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-3">
      Simpan
    </button>
  </form>
</template>
