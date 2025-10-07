<script setup lang="ts">
import { ref, watchEffect } from 'vue'
import type { Invoice } from '~/composables/useInvoices'

const props = defineProps<{ modelValue: Invoice }>()
const emit = defineEmits(['update:modelValue', 'submit'])

const form = ref<Invoice>({ ...props.modelValue })

// setiap kali props berubah (misal data lama udah di-fetch), sync ulang
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
      <span class="text-sm text-gray-700">Organization ID</span>
      <input v-model="form.organization_id" type="number" class="border p-2 w-full" />
    </label>

    <label>
      <span class="text-sm text-gray-700">Date</span>
      <input v-model="form.date" type="date" class="border p-2 w-full" />
    </label>

    <label>
      <span class="text-sm text-gray-700">Current Expiry</span>
      <input v-model="form.current_expiry" type="datetime-local" class="border p-2 w-full" />
    </label>

    <label>
      <span class="text-sm text-gray-700">New Expiry</span>
      <input v-model="form.new_expiry" type="datetime-local" class="border p-2 w-full" />
    </label>

    <label>
      <span class="text-sm text-gray-700">Total</span>
      <input v-model="form.total" type="number" step="0.01" class="border p-2 w-full" />
    </label>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-3">
      Simpan Perubahan
    </button>
  </form>
</template>
