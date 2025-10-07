<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoles } from '~/composables/useRoles'

const { updateRole } = useRoles()
const route = useRoute()
const router = useRouter()
const api = useRuntimeConfig().public.apiBase

const form = ref({
  organization_id: '',
  name: ''
})

const loading = ref(true)

onMounted(async () => {
  try {
    const { data, error } = await useFetch(`${api}/roles/${route.params.id}`)
    if (!error.value && data.value) {
      form.value = {
        organization_id: data.value.organization_id ?? '',
        name: data.value.name ?? ''
      }
    }
  } catch (err) {
    console.error(err)
  } finally {
    loading.value = false
  }
})

const submit = async () => {
  await updateRole(route.params.id, form.value)
  router.push('/roles')
}
</script>

<template>
  <div class="p-6 max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-4">Edit Role</h1>

    <div v-if="loading" class="text-gray-500">Loading...</div>

    <form v-else @submit.prevent="submit" class="flex flex-col gap-3">
      <input
        v-model="form.organization_id"
        type="number"
        placeholder="Organization ID"
        class="border p-2 rounded"
      />
      <input
        v-model="form.name"
        placeholder="Role Name"
        class="border p-2 rounded"
      />
      <button type="submit" class="bg-yellow-500 text-white py-2 rounded">
        Update
      </button>
    </form>
  </div>
</template>
