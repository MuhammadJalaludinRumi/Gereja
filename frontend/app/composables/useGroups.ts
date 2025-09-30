export const useGroups = () => {
  const baseUrl =
    'http://localhost:8000/api/groups'

  const getGroups = async () => {
    const { data, error } = await useFetch(baseUrl)
    return { data, error }
  }

  const getGroup = async (id: number) => {
    const { data, error } = await useFetch(`${baseUrl}/${id}`)
    return { data, error }
  }

  const createGroup = async (payload: any) => {
    const { data, error } = await useFetch(baseUrl, {
      method: 'POST',
      body: payload
    })
    return { data, error }
  }

  const updateGroup = async (id: number, payload: any) => {
    const { data, error } = await useFetch(`${baseUrl}/${id}`, {
      method: 'PUT',
      body: payload
    })
    return { data, error }
  }

  const deleteGroup = async (id: number) => {
    const { data, error } = await useFetch(`${baseUrl}/${id}`, {
      method: 'DELETE'
    })
    return { data, error }
  }

  return { getGroups, getGroup, createGroup, updateGroup, deleteGroup }
}
