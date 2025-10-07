export const useRules = () => {
    const apiUrl = 'http://127.0.0.1:8000/api/rules'
  
    const getRules = async () => {
      return await $fetch(apiUrl)
    }
  
    const getRule = async (id: string | number) => {
      return await $fetch(`${apiUrl}/${id}`)
    }
  
    const createRule = async (payload: any) => {
      return await $fetch(apiUrl, {
        method: 'POST',
        body: payload
      })
    }
  
    const updateRule = async (id: string | number, payload: any) => {
      return await $fetch(`${apiUrl}/${id}`, {
        method: 'PUT',
        body: payload
      })
    }
  
    const deleteRule = async (id: string | number) => {
      return await $fetch(`${apiUrl}/${id}`, {
        method: 'DELETE'
      })
    }
  
    return {
      getRules,
      getRule,
      createRule,
      updateRule,
      deleteRule
    }
  }
  