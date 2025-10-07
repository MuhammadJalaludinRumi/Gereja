const API_URL = "http://127.0.0.1:8000/api/acls"

export const useAcls = () => {
  const acls = ref<any[]>([])
  const acl = ref<any>(null)
  const loading = ref(false)
  const error = ref<string | null>(null)

  const fetchAll = async () => {
    loading.value = true
    try {
      const res = await fetch(API_URL)
      acls.value = await res.json()
    } catch (err) {
      error.value = "Gagal memuat data ACL"
    } finally {
      loading.value = false
    }
  }

  const fetchById = async (id: number) => {
    loading.value = true
    try {
      const res = await fetch(`${API_URL}/${id}`)
      acl.value = await res.json()
    } catch (err) {
      error.value = "Gagal memuat data ACL"
    } finally {
      loading.value = false
    }
  }

  const create = async (payload: { name: string }) => {
    const res = await fetch(API_URL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    })
    if (!res.ok) throw new Error("Gagal membuat ACL")
    return await res.json()
  }

  const update = async (id: number, payload: { name: string }) => {
    const res = await fetch(`${API_URL}/${id}`, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    })
    if (!res.ok) throw new Error("Gagal memperbarui ACL")
    return await res.json()
  }

  const remove = async (id: number) => {
    const res = await fetch(`${API_URL}/${id}`, { method: "DELETE" })
    if (!res.ok) throw new Error("Gagal menghapus ACL")
  }

  return { acls, acl, fetchAll, fetchById, create, update, remove, loading, error }
}
