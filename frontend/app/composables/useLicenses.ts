export const useLicenses = () => {
    const apiUrl = 'http://127.0.0.1:8000/api/licenses'
  
    const getLicenses = async () => {
      return await $fetch(apiUrl)
    }
  
    const getLicense = async (id: number) => {
      return await $fetch(`${apiUrl}/${id}`)
    }
  
    const createLicense = async (payload: any) => {
      return await $fetch(apiUrl, {
        method: 'POST',
        body: payload
      })
    }
  
    const updateLicense = async (id: number, payload: any) => {
      return await $fetch(`${apiUrl}/${id}`, {
        method: 'PUT',
        body: payload
      })
    }
  
    const deleteLicense = async (id: number) => {
      return await $fetch(`${apiUrl}/${id}`, {
        method: 'DELETE'
      })
    }
  
    return {
      getLicenses,
      getLicense,
      createLicense,
      updateLicense,
      deleteLicense
    }
  }
  