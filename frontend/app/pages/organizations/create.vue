<template>
  <div class="p-6 max-w-xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Buat Organization Baru</h1>

    <form @submit.prevent="save" class="space-y-3">
      <input v-model="form.name" placeholder="Name" required class="w-full border p-2 rounded" />
      <input v-model="form.abbreviation" placeholder="Abbreviation" class="w-full border p-2 rounded" />
      <input v-model="form.address" placeholder="Address" class="w-full border p-2 rounded" />
      <input v-model="form.city" placeholder="City" class="w-full border p-2 rounded" />
      <input v-model="form.latitude" type="number" placeholder="Latitude" class="w-full border p-2 rounded" />
      <input v-model="form.longitude" type="number" placeholder="Longitude" class="w-full border p-2 rounded" />
      <input v-model="form.phone" placeholder="Phone" class="w-full border p-2 rounded" />
      <input v-model="form.email" type="email" placeholder="Email" class="w-full border p-2 rounded" />
      <input v-model="form.website" placeholder="Website" class="w-full border p-2 rounded" />
      <input v-model="form.logo" placeholder="Logo URL" class="w-full border p-2 rounded" />
      <input v-model="form.founded" type="date" placeholder="Founded" class="w-full border p-2 rounded" />
      <input v-model="form.legal" placeholder="Legal" class="w-full border p-2 rounded" />

      <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        Simpan
      </button>
    </form>
  </div>
</template>

<script setup lang="ts">
import { reactive } from 'vue'
import { useOrganizations } from '~/composables/useOrganizations'
import { useRouter } from 'vue-router'

const router = useRouter()
const { createOrganization } = useOrganizations()

const form = reactive({
  name: '',
  abbreviation: '',
  address: '',
  city: '',
  latitude: null,
  longitude: null,
  phone: '',
  email: '',
  website: '',
  logo: '',
  founded: '',
  legal: '',
})

const save = async () => {
  await createOrganization(form)
  router.push('/organizations')
}
</script>
