import { ref } from 'vue'

export function useRoles() {
  const roles = ref([])
  const api = useRuntimeConfig().public.apiBase

  const fetchRoles = async () => {
    const { data } = await useFetch(`${api}/roles`)
    roles.value = data.value
  }

  const createRole = async (payload: any) => {
    await $fetch(`${api}/roles`, {
      method: 'POST',
      body: payload
    })
    await fetchRoles()
  }

  const updateRole = async (id: number, payload: any) => {
    await $fetch(`${api}/roles/${id}`, {
      method: 'PUT',
      body: payload
    })
    await fetchRoles()
  }

  const deleteRole = async (id: number) => {
    await $fetch(`${api}/roles/${id}`, {
      method: 'DELETE'
    })
    await fetchRoles()
  }

  return { roles, fetchRoles, createRole, updateRole, deleteRole }
}
