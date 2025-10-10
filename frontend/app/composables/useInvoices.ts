import { ref } from 'vue'

export interface Invoice {
  id?: number
  organization_id: number
  date: string
  current_expiry: string
  new_expiry: string
  total: number
}

export const useInvoices = () => {
  const invoices = ref<Invoice[]>([])
  const baseUrl = useRuntimeConfig().public.apiBase + '/invoices'

  const fetchInvoices = async () => {
    const { data } = await useFetch<Invoice[]>(baseUrl)
    invoices.value = data.value || []
  }

  const createInvoice = async (payload: Invoice) => {
    const newInvoice = await $fetch<Invoice>(baseUrl, { method: 'POST', body: payload })
    invoices.value.push(newInvoice)
  }

  const updateInvoice = async (id: number, payload: Partial<Invoice>) => {
    const updated = await $fetch<Invoice>(`${baseUrl}/${id}`, { method: 'PUT', body: payload })

    const index = invoices.value.findIndex(inv => inv.id === id)
    if (index !== -1) invoices.value[index] = { ...invoices.value[index], ...updated }
  }

  const deleteInvoice = async (id: number) => {
    await $fetch(`${baseUrl}/${id}`, { method: 'DELETE' })
    invoices.value = invoices.value.filter(inv => inv.id !== id)
  }

  return { invoices, fetchInvoices, createInvoice, updateInvoice, deleteInvoice }
}
