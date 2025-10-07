<script setup lang="ts">
import { ref } from 'vue'
import { useRoles } from '~/composables/useRoles'

const { createRole } = useRoles()
const router = useRouter()

const form = ref({
  organization: '',
  name: ''
})

const submit = async () => {
  await createRole(form.value)
  router.push('/roles')
}
</script>

<template>
  <div class="p-6 max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-4">Create Role</h1>
    <form @submit.prevent="submit" class="flex flex-col gap-3">
      <input
        v-model="form.organization"
        type="number"
        placeholder="Organization ID"
        class="border p-2 rounded"
      />
      <input
        v-model="form.name"
        placeholder="Role Name"
        class="border p-2 rounded"
      />
      <button class="bg-blue-500 text-white py-2 rounded">Save</button>
    </form>
  </div>
</template>
