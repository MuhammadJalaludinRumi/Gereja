<script setup lang="ts">
import { useGroups } from '~/composables/useGroups'
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'

const { createGroup } = useGroups()
const router = useRouter()
const form = reactive({
  name: '',
  address: '',
  city: '',
  phone: '',
  email: '',
  website: '',
  founded: '',
  legal: '',
})
const logoFile = ref<File | null>(null)

const submit = async () => {
  const fd = new FormData()
  Object.entries(form).forEach(([key, val]) => fd.append(key, val || ''))
  if (logoFile.value) fd.append('logo', logoFile.value)

  const { error } = await createGroup(fd)
  if (!error.value) {
    router.push('/groups')
  } else {
    alert('Gagal nyimpen bro 😭')
  }
}
</script>

<template>
  <div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Create Group</h1>

    <form @submit.prevent="submit" class="grid gap-4">
      <input v-model="form.name" placeholder="Name" class="border p-2 rounded" required />
      <input v-model="form.address" placeholder="Address" class="border p-2 rounded" />
      <input v-model="form.city" placeholder="City" class="border p-2 rounded" />
      <input v-model="form.phone" placeholder="Phone" class="border p-2 rounded" />
      <input v-model="form.email" type="email" placeholder="Email" class="border p-2 rounded" />
      <input v-model="form.website" placeholder="Website" class="border p-2 rounded" />
      <input type="file" accept="image/*" @change="e => logoFile.value = e.target.files?.[0] || null" class="border p-2 rounded" />
      <input v-model="form.founded" type="date" class="border p-2 rounded" />
      <input v-model="form.legal" placeholder="Legal" class="border p-2 rounded" />

      <div class="flex gap-2">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
        <NuxtLink to="/groups" class="bg-gray-300 px-4 py-2 rounded">Cancel</NuxtLink>
      </div>
    </form>
  </div>
</template>
