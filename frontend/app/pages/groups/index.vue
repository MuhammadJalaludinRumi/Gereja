<script setup lang="ts">
import { useGroups } from '~/composables/useGroups'

const { getGroups, deleteGroup } = useGroups()
const groups = ref<any[]>([])

onMounted(async () => {
  const { data } = await getGroups()
  groups.value = data.value || []
})

const config = useRuntimeConfig()

const formatDate = (value: string | null) => {
  if (!value) return ''
  return value.split('T')[0]
}

const remove = async (id: number) => {
  await deleteGroup(id)
  groups.value = groups.value.filter(g => g.id !== id)
}
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Groups</h1>
    <NuxtLink to="/groups/create" class="bg-blue-500 text-white px-3 py-2 rounded">+ New</NuxtLink>
    <table class="mt-4 w-full border">
      <thead>
        <tr class="bg-gray-100">
          <th class="p-2">Name</th>
          <th class="p-2">Address</th>
          <th class="p-2">City</th>
          <th class="p-2">Phone</th>
          <th class="p-2">Email</th>
          <th class="p-2">Website</th>
          <th class="p-2">Logo</th>
          <th class="p-2">Founded</th>
          <th class="p-2">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="g in groups" :key="g.id" class="border-t">
          <td class="p-2">{{ g.name }}</td>
          <td class="p-2">{{ g.address }}</td>
          <td class="p-2">{{ g.city?.name }}</td>
          <td class="p-2">{{ g.phone }}</td>
          <td class="p-2">{{ g.email }}</td>
          <td class="p-2">{{ g.website }}</td>
          <td class="p-2">
            <img v-if="g.logo" :src="g.logo" alt="logo" class="w-12 h-12 object-cover rounded" />
            <span v-else class="text-gray-400">No logo</span>
          </td>
          <td class="p-2">{{ formatDate(g.founded) }}</td>
          <td class="p-2">
            <NuxtLink :to="`/groups/${g.id}`" class="text-blue-500">Edit</NuxtLink>
            <button @click="remove(g.id)" class="ml-2 text-red-500">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
