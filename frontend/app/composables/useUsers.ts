import { ref } from 'vue'

interface User {
  id?: number
  username: string
  password?: string
  name: string
  is_active: boolean
  roles_id: number
}

export function useUsers() {
  const users = ref<User[]>([])
  const api = useRuntimeConfig().public.apiBase

  const fetchUsers = async () => {
    const { data } = await useFetch<User[]>(`${api}/users`)
    if (data.value) users.value = data.value
  }

  const createUser = async (payload: User) => {
    await $fetch(`${api}/users`, {
      method: 'POST',
      body: payload
    })
    await fetchUsers()
  }

  const updateUser = async (id: number, payload: Partial<User>) => {
    await $fetch(`${api}/users/${id}`, {
      method: 'PUT',
      body: payload
    })
    await fetchUsers()
  }

  const deleteUser = async (id: number) => {
    await $fetch(`${api}/users/${id}`, {
      method: 'DELETE'
    })
    await fetchUsers()
  }

  return { users, fetchUsers, createUser, updateUser, deleteUser }
}
