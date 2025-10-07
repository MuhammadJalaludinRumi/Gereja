// composables/useUserAuthorities.ts
import { ref } from 'vue'

const API_URL = 'http://127.0.0.1:8000/api/user-authorities'

export function useUserAuthorities() {
  const userAuthorities = ref<any[]>([])
  const userAuthority = ref<any | null>(null)
  const loading = ref(false)
  const error = ref<string | null>(null)

  const parseResponse = async (res: Response) => {
    const ct = res.headers.get('content-type') || ''
    if (ct.includes('application/json')) {
      return res.json()
    }
    // fallback: return plaintext (html or other)
    return res.text()
  }

  const handleResponse = async (res: Response) => {
    const body = await parseResponse(res)
    if (!res.ok) {
      // Try to extract a friendly message
      let msg = ''
      if (typeof body === 'string') {
        msg = body
      } else if (body && (body as any).message) {
        msg = (body as any).message
      } else {
        msg = JSON.stringify(body)
      }
      throw new Error(`HTTP ${res.status}: ${msg}`)
    }
    return body
  }

  const fetchAll = async () => {
    loading.value = true
    error.value = null
    try {
      const res = await fetch(API_URL)
      const data = await handleResponse(res)
      userAuthorities.value = Array.isArray(data) ? data : []
      return userAuthorities.value
    } catch (err: any) {
      console.error('fetchAll error', err)
      error.value = err.message || String(err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const fetchById = async (id: number) => {
    loading.value = true
    error.value = null
    try {
      const res = await fetch(`${API_URL}/${id}`)
      const data = await handleResponse(res)
      userAuthority.value = data
      return data
    } catch (err: any) {
      console.error('fetchById error', err)
      error.value = err.message || String(err)
      throw err
    } finally {
      loading.value = false
    }
  }

  const create = async (payload: { user: number; role: number }) => {
    try {
      const res = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
        body: JSON.stringify(payload),
      })
      const data = await handleResponse(res)
      return data
    } catch (err) {
      console.error('create error', err)
      throw err
    }
  }

  const update = async (id: number, payload: { user: number; role: number }) => {
    try {
      const res = await fetch(`${API_URL}/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
        body: JSON.stringify(payload),
      })
      const data = await handleResponse(res)
      return data
    } catch (err) {
      console.error('update error', err)
      throw err
    }
  }

  const remove = async (id: number) => {
    try {
      const res = await fetch(`${API_URL}/${id}`, {
        method: 'DELETE',
        headers: { Accept: 'application/json' },
      })
      // some APIs return 204 with empty body
      if (!res.ok) {
        const body = await parseResponse(res)
        throw new Error(`HTTP ${res.status}: ${typeof body === 'string' ? body : JSON.stringify(body)}`)
      }
    } catch (err) {
      console.error('delete error', err)
      throw err
    }
  }

  return {
    userAuthorities,
    userAuthority,
    loading,
    error,
    fetchAll,
    fetchById,
    create,
    update,
    remove,
  }
}
