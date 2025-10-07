<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useOrganizationLicenses } from '~/composables/useOrganizationLicenses'

const { create } = useOrganizationLicenses()
const router = useRouter()

const form = ref({
  organization_id: '',
  license_id: '',
  max_member: '',
  is_active: true,
  expiry: ''
})

const submit = async () => {
  await create(form.value)
  router.push('/organizationLicense')
}
</script>

<template>
  <div class="p-6 max-w-md mx-auto">
    <h1 class="text-xl font-bold mb-4">Tambah License</h1>

    <form @submit.prevent="submit" class="space-y-3">
      <input v-model="form.organization_id" type="number" placeholder="Organization ID" class="input input-bordered w-full" />
      <input v-model="form.license_id" type="number" placeholder="License ID" class="input input-bordered w-full" />
      <input v-model="form.max_member" type="number" placeholder="Max Member" class="input input-bordered w-full" />
      <label class="flex items-center space-x-2">
        <input v-model="form.is_active" type="checkbox" /> <span>Active</span>
      </label>
      <input v-model="form.expiry" type="datetime-local" class="input input-bordered w-full" />
      <button type="submit" class="btn btn-primary w-full">Simpan</button>
    </form>
  </div>
</template>
