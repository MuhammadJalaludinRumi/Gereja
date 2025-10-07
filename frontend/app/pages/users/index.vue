<script setup lang="ts">
import { useUsers } from '~/composables/useUsers'
const { users, fetchUsers, deleteUser } = useUsers()
onMounted(fetchUsers)

const router = useRouter()

const goToEdit = (id: number) => {
  router.push(`/users/${id}`)
}

const goToCreate = () => {
  router.push('/users/create')
}
</script>

<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold">Users</h1>
      <button class="bg-blue-500 text-white px-3 py-1 rounded" @click="goToCreate">
        + Add User
      </button>
    </div>

    <table class="min-w-full border">
      <thead>
        <tr class="bg-gray-100">
          <th class="p-2 text-left">ID</th>
          <th class="p-2 text-left">Username</th>
          <th class="p-2 text-left">Name</th>
          <th class="p-2 text-left">Active</th>
          <th class="p-2 text-left">Role</th>
          <th class="p-2 text-left">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id" class="border-t">
          <td class="p-2">{{ user.id }}</td>
          <td class="p-2">{{ user.username }}</td>
          <td class="p-2">{{ user.name }}</td>
          <td class="p-2">{{ user.is_active ? 'active' : 'inactive' }}</td>
          <td class="p-2">{{ user.role_id }}</td>
          <td class="p-2 flex gap-2">
            <button class="bg-yellow-400 px-2 py-1 rounded" @click="goToEdit(user.id)">Edit</button>
            <button class="bg-red-500 text-white px-2 py-1 rounded" @click="deleteUser(user.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
