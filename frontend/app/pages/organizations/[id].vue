<template>
  <div class="p-6 max-w-xl mx-auto" v-if="organization">
    <h1 class="text-2xl font-bold mb-4">Edit Organization</h1>

    <form @submit.prevent="update" class="space-y-3">
      <input v-model="organization.name" placeholder="Name" required class="w-full border p-2 rounded" />
      <input v-model="organization.abbreviation" placeholder="Abbreviation" class="w-full border p-2 rounded" />
      <input v-model="organization.address" placeholder="Address" class="w-full border p-2 rounded" />
      <input v-model="organization.city" placeholder="City" class="w-full border p-2 rounded" />
      <input v-model="organization.latitude" type="number" placeholder="Latitude" class="w-full border p-2 rounded" />
      <input v-model="organization.longitude" type="number" placeholder="Longitude" class="w-full border p-2 rounded" />
      <input v-model="organization.phone" placeholder="Phone" class="w-full border p-2 rounded" />
      <input v-model="organization.email" type="email" placeholder="Email" class="w-full border p-2 rounded" />
      <input v-model="organization.website" placeholder="Website" class="w-full border p-2 rounded" />
      <input v-model="organization.logo" placeholder="Logo URL" class="w-full border p-2 rounded" />
      <input v-model="organization.founded" type="date" placeholder="Founded" class="w-full border p-2 rounded" />
      <input v-model="organization.legal" placeholder="Legal" class="w-full border p-2 rounded" />

      <div class="flex gap-4">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Update
        </button>
        <button type="button" @click="remove" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
          Delete
        </button>
      </div>
    </form>
  </div>

  <p v-else class="text-gray-500">Loading...</p>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useOrganizations } from '~/composables/useOrganizations'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()
const { getOrganization, updateOrganization, deleteOrganization } = useOrganizations()

const organization = ref<any>(null)

onMounted(async () => {
  organization.value = await getOrganization(route.params.id as string)
})

const update = async () => {
  await updateOrganization(route.params.id as string, organization.value)
  router.push('/organizations')
}

const remove = async () => {
  if (confirm('Yakin ingin menghapus data ini?')) {
    await deleteOrganization(route.params.id as string)
    router.push('/organizations')
  }
}
</script>
