<script setup lang="ts">
import { useGroups } from '~/composables/useGroups'

const route = useRoute()
const router = useRouter()
const { getGroup, updateGroup } = useGroups()

// state form
const form = reactive({
  name: '',
  address: '',
  city: '',
  phone: '',
  email: '',
  website: '',
  logo: '',
  founded: '',
  legal: '',
})

// load data awal
onMounted(async () => {
  const { data } = await getGroup(Number(route.params.id))
  if (data.value) {
    Object.assign(form, data.value)
    // kalau kolom founded date, ubah ke format yyyy-mm-dd biar cocok dengan <input type="date">
    if (form.founded) {
      form.founded = form.founded.split('T')[0]
    }
  }
})

const submit = async () => {
  const { error } = await updateGroup(Number(route.params.id), form)
  if (!error.value) {
    router.push('/groups')
  } else {
    alert('Gagal update data')
  }
}
</script>

<template>
  <div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Edit Group</h1>

    <form @submit.prevent="submit" class="grid gap-4">
      <input v-model="form.name" placeholder="Name" class="border p-2 rounded" required />
      <input v-model="form.address" placeholder="Address" class="border p-2 rounded" />
      <input v-model="form.city" placeholder="City" class="border p-2 rounded" />
      <input v-model="form.phone" placeholder="Phone" class="border p-2 rounded" />
      <input v-model="form.email" type="email" placeholder="Email" class="border p-2 rounded" />
      <input v-model="form.website" placeholder="Website" class="border p-2 rounded" />
      <input v-model="form.logo" placeholder="Logo (URL/Path)" class="border p-2 rounded" />
      <input v-model="form.founded" type="date" class="border p-2 rounded" />
      <input v-model="form.legal" placeholder="Legal" class="border p-2 rounded" />

      <div class="flex gap-2">
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Update</button>
        <NuxtLink to="/groups" class="bg-gray-300 px-4 py-2 rounded">Cancel</NuxtLink>
      </div>
    </form>
  </div>
</template>
