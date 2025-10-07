import { ref } from 'vue'

export interface News {
  id?: number
  date_post: string
  title: string
  content: string
  thumbnail?: string
  image?: string
  status: number
}

export const useNews = () => {
  const news = ref<News[]>([])
  const baseUrl = useRuntimeConfig().public.apiBase + '/news'

  const fetchNews = async () => {
    const { data } = await useFetch<News[]>(baseUrl)
    news.value = data.value || []
  }

  const createNews = async (payload: News) => {
    await $fetch(baseUrl, { method: 'POST', body: payload })
    await fetchNews()
  }

  const updateNews = async (id: number, payload: Partial<News>) => {
    await $fetch(`${baseUrl}/${id}`, { method: 'PUT', body: payload })
    await fetchNews()
  }

  const deleteNews = async (id: number) => {
    await $fetch(`${baseUrl}/${id}`, { method: 'DELETE' })
    await fetchNews()
  }

  return { news, fetchNews, createNews, updateNews, deleteNews }
}
