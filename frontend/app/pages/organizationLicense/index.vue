<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useOrganizationLicenses } from '~/composables/useOrganizationLicenses'

const { getAll, remove } = useOrganizationLicenses()
const licenses = ref<any[]>([])

const fetchData = async () => {
  licenses.value = await getAll()
}

const deleteItem = async (id: number) => {
  if (confirm('Yakin mau hapus bro?')) {
    await remove(id)
    await fetchData()
  }
}

onMounted(fetchData)
</script>

<template>
  <div class="p-6">
    <div class="flex justify-between mb-4">
      <h1 class="text-xl font-bold">Organization Licenses</h1>
      <NuxtLink to="/organizationLicense/create" class="btn btn-primary">+ Tambah</NuxtLink>
    </div>

    <table class="table-auto w-full border">
      <thead class="bg-gray-100">
        <tr>
          <th>ID</th>
          <th>Organization</th>
          <th>License</th>
          <th>Max Member</th>
          <th>Active</th>
          <th>Expiry</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="l in licenses" :key="l.id">
          <td>{{ l.id }}</td>
          <td>{{ l.organization_id }}</td>
          <td>{{ l.license_id }}</td>
          <td>{{ l.max_member }}</td>
          <td>{{ l.is_active ? '✅' : '❌' }}</td>
          <td>{{ l.expiry }}</td>
          <td>
            <NuxtLink :to="`/organizationLicense/${l.id}`" class="text-blue-500 mr-2">Edit</NuxtLink>
            <button @click="deleteItem(l.id)" class="text-red-500">Hapus</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
