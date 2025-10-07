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
    await $fetch(baseUrl, { method: 'POST', body: payload })
    await fetchInvoices()
  }

  const updateInvoice = async (id: number, payload: Partial<Invoice>) => {
    await $fetch(`${baseUrl}/${id}`, { method: 'PUT', body: payload })
    await fetchInvoices()
  }

  const deleteInvoice = async (id: number) => {
    await $fetch(`${baseUrl}/${id}`, { method: 'DELETE' })
    await fetchInvoices()
  }

  return { invoices, fetchInvoices, createInvoice, updateInvoice, deleteInvoice }
}
