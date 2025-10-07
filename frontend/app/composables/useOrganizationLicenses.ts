export const useOrganizationLicenses = () => {
  const api = useFetchApi('/organization-licenses')

  const getAll = async () => {
    const { data } = await api.get()
    return data.value
  }

  const getById = async (id: number) => {
    const { data } = await api.get(`/${id}`)
    return data.value
  }

  const create = async (payload: any) => {
    await api.post('', payload)
  }

  const update = async (id: number, payload: any) => {
    await api.put(`/${id}`, payload)
  }

  const remove = async (id: number) => {
    await api.del(`/${id}`)
  }

  return { getAll, getById, create, update, remove }
}

// helper api composable
const useFetchApi = (baseUrl: string) => {
  const config = useRuntimeConfig()
  const base = config.public.apiBase
  return {
    get: (url = '') => useFetch(base + baseUrl + url),
    post: (url = '', body: any) => $fetch(base + baseUrl + url, { method: 'POST', body }),
    put: (url = '', body: any) => $fetch(base + baseUrl + url, { method: 'PUT', body }),
    del: (url = '') => $fetch(base + baseUrl + url, { method: 'DELETE' }),
  }
}
