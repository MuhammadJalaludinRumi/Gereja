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

  const updateOrganization = async (id: string, data: any, isFormData = false) => {
    return await $fetch(`http://127.0.0.1:8000/api/organizations/${id}`, {
      method: isFormData ? 'POST' : 'PUT', // <== pakai POST kalo FormData (karena kita spoof PUT)
      body: data,
      headers: isFormData
        ? undefined // biar browser auto set multipart boundary
        : { 'Content-Type': 'application/json' },
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
