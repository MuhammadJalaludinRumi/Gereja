<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold">Daftar Organization</h1>
      <NuxtLink
        to="/organizations/create"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        + Tambah Organization
      </NuxtLink>
    </div>

    <div v-if="organizations.length" class="overflow-x-auto">
      <table class="min-w-full border text-sm">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-2 border">Name</th>
            <th class="p-2 border">Abbreviation</th>
            <th class="p-2 border">Address</th>
            <th class="p-2 border">City</th>
            <th class="p-2 border">Latitude</th>
            <th class="p-2 border">Longitude</th>
            <th class="p-2 border">Phone</th>
            <th class="p-2 border">Email</th>
            <th class="p-2 border">Website</th>
            <th class="p-2 border">Logo</th>
            <th class="p-2 border">Founded</th>
            <th class="p-2 border">Legal</th>
            <th class="p-2 border">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="org in organizations" :key="org.id" class="hover:bg-gray-50">
            <td class="p-2 border">{{ org.name }}</td>
            <td class="p-2 border">{{ org.abbreviation }}</td>
            <td class="p-2 border">{{ org.address }}</td>
            <td class="p-2 border">{{ org.city }}</td>
            <td class="p-2 border">{{ org.latitude }}</td>
            <td class="p-2 border">{{ org.longitude }}</td>
            <td class="p-2 border">{{ org.phone }}</td>
            <td class="p-2 border">{{ org.email }}</td>
            <td class="p-2 border">
              <a v-if="org.website" :href="org.website" target="_blank" class="text-blue-500 underline">
                {{ org.website }}
              </a>
            </td>
            <td class="p-2 border">
              <img v-if="org.logo" :src="org.logo" alt="Logo" class="h-10" />
            </td>
            <td class="p-2 border">{{ formatDate(org.founded) }}</td>
            <td class="p-2 border">{{ org.legal }}</td>
            <td class="p-2 border flex gap-2">
              <NuxtLink
                :to="`/organizations/${org.id}`"
                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
              >
                Edit
              </NuxtLink>
              <button
                @click="remove(org.id)"
                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <p v-else class="text-gray-500">Belum ada data organization.</p>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useOrganizations } from '~/composables/useOrganizations'

const organizations = ref<any[]>([])
const { getOrganizations, deleteOrganization } = useOrganizations()

const fetchData = async () => {
  organizations.value = await getOrganizations()
}

onMounted(fetchData)

const remove = async (id: string) => {
  if (confirm('Yakin ingin menghapus data ini?')) {
    await deleteOrganization(id)
    await fetchData()
  }
}

const formatDate = (value: string | null) => {
  if (!value) return ''
  return value.split('T')[0]
}
</script>
