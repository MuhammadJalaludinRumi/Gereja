<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useLicenses } from '~/composables/useLicenses'

const router = useRouter()
const route = useRoute()
const { getLicense, updateLicense } = useLicenses()

const form = ref({
  name: '',
  price: ''
})

const saving = ref(false)
const error = ref<string | null>(null)

const loadLicense = async () => {
  try {
    const data = await getLicense(route.params.id as string)
    form.value = { name: data.name, price: data.price }
  } catch (e) {
    console.error('Gagal memuat lisensi:', e)
  }
}

const update = async () => {
  saving.value = true
  try {
    await updateLicense(route.params.id as string, form.value)
    router.push('/licenses')
  } catch (err) {
    error.value = 'Gagal memperbarui lisensi'
  } finally {
    saving.value = false
  }
}

onMounted(loadLicense)
</script>

<template>
  <div class="p-6 w-full" style="color: var(--ui-text); background: var(--ui-bg);">
    <h1 class="text-2xl font-bold mb-6" style="color: var(--ui-text-highlighted);">
      Edit Lisensi
    </h1>

    <UCard class="max-w-md">
      <form @submit.prevent="update" class="space-y-4">
        <div>
          <label class="block mb-1 font-medium">Nama Lisensi</label>
          <UInput v-model="form.name" placeholder="Masukkan nama lisensi" />
        </div>

        <div>
          <label class="block mb-1 font-medium">Harga (Rp)</label>
          <UInput v-model="form.price" type="number" placeholder="Masukkan harga" />
        </div>

        <div class="flex gap-3">
          <UButton type="submit" :loading="saving" color="primary" label="Perbarui" />
          <UButton color="gray" variant="soft" label="Kembali" @click="router.push('/licenses')" />
        </div>

        <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>
      </form>
    </UCard>
  </div>
</template>
