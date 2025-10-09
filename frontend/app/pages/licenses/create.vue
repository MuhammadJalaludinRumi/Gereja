<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useLicenses } from '~/composables/useLicenses'

const router = useRouter()
const { createLicense } = useLicenses()

const form = ref({
  name: '',
  price: ''
})

const saving = ref(false)
const error = ref<string | null>(null)

const save = async () => {
  if (!form.value.name || !form.value.price) {
    error.value = 'Nama dan harga wajib diisi'
    return
  }

  saving.value = true
  try {
    await createLicense(form.value)
    router.push('/licenses')
  } catch (err) {
    error.value = 'Gagal menyimpan lisensi'
  } finally {
    saving.value = false
  }
}
</script>

<template>
  <div class="p-6 w-full" style="color: var(--ui-text); background: var(--ui-bg);">
    <h1 class="text-2xl font-bold mb-6" style="color: var(--ui-text-highlighted);">
      Tambah Lisensi Baru
    </h1>

    <UCard class="max-w-md">
      <form @submit.prevent="save" class="space-y-4">
        <div>
          <label class="block mb-1 font-medium">Nama Lisensi</label>
          <UInput v-model="form.name" placeholder="Masukkan nama lisensi" />
        </div>

        <div>
          <label class="block mb-1 font-medium">Harga (Rp)</label>
          <UInput v-model="form.price" type="number" placeholder="Masukkan harga" />
        </div>

        <div class="flex gap-3">
          <UButton type="submit" :loading="saving" color="primary" label="Simpan" />
          <UButton color="gray" variant="soft" label="Batal" @click="router.push('/licenses')" />
        </div>

        <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>
      </form>
    </UCard>
  </div>
</template>
