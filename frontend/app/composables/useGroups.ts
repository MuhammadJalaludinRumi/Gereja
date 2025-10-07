export const useGroups = () => {
  const config = useRuntimeConfig()
  const baseUrl = `${config.public.apiBase}/groups`

  const getGroups = async () => {
    const { data, error } = await useFetch(baseUrl)
    return { data, error }
  }

  const getGroup = async (id: number) => {
    const { data, error } = await useFetch(`${baseUrl}/${id}`)
    return { data, error }
  }

  // 🔹 Create group - pakai fetch native
  const createGroup = async (formData: FormData) => {
    try {
      const res = await fetch(baseUrl, {
        method: 'POST',
        body: formData,
      })
      const data = await res.json()
      if (!res.ok) throw new Error(data.message || 'Gagal upload')
      return { data, error: null }
    } catch (error) {
      return { data: null, error }
    }
  }

  // 🔹 Update group
  const updateGroup = async (id: number, payload: any) => {
    const isFormData = payload instanceof FormData
    if (isFormData) payload.append('_method', 'PUT')

    try {
      const res = await fetch(`${baseUrl}/${id}`, {
        method: isFormData ? 'POST' : 'PUT',
        body: isFormData ? payload : JSON.stringify(payload),
        headers: isFormData ? undefined : { 'Content-Type': 'application/json' },
      })
      const data = await res.json()
      if (!res.ok) throw new Error(data.message || 'Gagal update')
      return { data, error: null }
    } catch (error) {
      return { data: null, error }
    }
  }

  const deleteGroup = async (id: number) => {
    const { data, error } = await useFetch(`${baseUrl}/${id}`, { method: 'DELETE' })
    return { data, error }
  }

  return { getGroups, getGroup, createGroup, updateGroup, deleteGroup }
}
