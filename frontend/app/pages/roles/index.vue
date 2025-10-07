<script setup lang="ts">
import { onMounted } from 'vue'
import { useRoles } from '~/composables/useRoles'

const { roles, fetchRoles, deleteRole } = useRoles()
const router = useRouter()

onMounted(fetchRoles)

const handleDelete = async (id: number) => {
  if (confirm('Yakin mau hapus role ini?')) {
    await deleteRole(id)
  }
}
</script>

<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold">Roles</h1>
      <button
        @click="router.push('/roles/create')"
        class="bg-blue-500 text-white px-3 py-2 rounded"
      >
        + Add Role
      </button>
    </div>

    <table class="w-full border">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="p-2 border">ID</th>
          <th class="p-2 border">Organization</th>
          <th class="p-2 border">Name</th>
          <th class="p-2 border">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="role in roles" :key="role.id">
          <td class="p-2 border">{{ role.id }}</td>
          <td class="p-2 border">{{ role.organization_id }}</td>
          <td class="p-2 border">{{ role.name }}</td>
          <td class="p-2 border flex gap-2">
            <button
              class="bg-yellow-500 text-white px-3 py-1 rounded"
              @click="router.push(`/roles/${role.id}`)"
            >
              Edit
            </button>
            <button
              class="bg-red-500 text-white px-3 py-1 rounded"
              @click="handleDelete(role.id)"
            >
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
