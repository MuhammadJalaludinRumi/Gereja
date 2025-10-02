export const useOrganizations = () => {
  const apiUrl = 'http://127.0.0.1:8000/api/organizations'

  const getOrganizations = async () => {
    return await $fetch(apiUrl)
  }

  const getOrganization = async (id: string) => {
    return await $fetch(`${apiUrl}/${id}`)
  }

  const createOrganization = async (payload: any) => {
    return await $fetch(apiUrl, {
      method: 'POST',
      body: payload
    })
  }

  const updateOrganization = async (id: string, payload: any) => {
    return await $fetch(`${apiUrl}/${id}`, {
      method: 'PUT',
      body: payload
    })
  }

  const deleteOrganization = async (id: string) => {
    return await $fetch(`${apiUrl}/${id}`, {
      method: 'DELETE'
    })
  }

  return {
    getOrganizations,
    getOrganization,
    createOrganization,
    updateOrganization,
    deleteOrganization
  }
}
