<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useOrganizationLicenses } from '~/composables/useOrganizationLicenses'

const { getById, update } = useOrganizationLicenses()
const route = useRoute()
const router = useRouter()
const form = ref<any>({})

onMounted(async () => {
  form.value = await getById(Number(route.params.id))
})

const submit = async () => {
  await update(Number(route.params.id), form.value)
  router.push('/organizationLicense')
}
</script>

<template>
  <div class="p-6 max-w-md mx-auto">
    <h1 class="text-xl font-bold mb-4">Edit License</h1>

    <form v-if="form" @submit.prevent="submit" class="space-y-3">
      <input v-model="form.organization_id" type="number" placeholder="Organization ID" class="input input-bordered w-full" />
      <input v-model="form.license_id" type="number" placeholder="License ID" class="input input-bordered w-full" />
      <input v-model="form.max_member" type="number" placeholder="Max Member" class="input input-bordered w-full" />
      <label class="flex items-center space-x-2">
        <input v-model="form.is_active" type="checkbox" /> <span>Active</span>
      </label>
      <input v-model="form.expiry" type="datetime-local" class="input input-bordered w-full" />
      <button type="submit" class="btn btn-primary w-full">Update</button>
    </form>
  </div>
</template>
