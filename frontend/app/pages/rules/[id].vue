<template>
    <div class="p-6 max-w-lg mx-auto">
      <h1 class="text-2xl font-bold mb-4">Edit Rule</h1>
  
      <form @submit.prevent="update">
        <div class="mb-4">
          <label class="block mb-1 font-medium">Role</label>
          <input
            v-model.number="form.role"
            type="number"
            class="w-full p-2 border border-gray-300 rounded"
            required
          />
        </div>
  
        <div class="mb-4">
          <label class="block mb-1 font-medium">ACL</label>
          <input
            v-model.number="form.acl"
            type="number"
            class="w-full p-2 border border-gray-300 rounded"
            required
          />
        </div>
  
        <div class="mb-4">
          <label class="block mb-1 font-medium">Permission</label>
          <select
            v-model="form.permission"
            class="w-full p-2 border border-gray-300 rounded"
            required
          >
            <option :value="true">Yes</option>
            <option :value="false">No</option>
          </select>
        </div>
  
        <button
          type="submit"
          class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded"
        >
          Update
        </button>
      </form>
    </div>
  </template>
  
  <script setup lang="ts">
  import { useRoute, useRouter } from 'vue-router'
  import { useRules } from '~/composables/useRules'
  
  const route = useRoute()
  const router = useRouter()
  const { getRule, updateRule } = useRules()
  
  const form = ref({
    role: 0,
    acl: 0,
    permission: false
  })
  
  onMounted(async () => {
    const data = await getRule(route.params.id as string)
    form.value = data
  })
  
  const update = async () => {
    await updateRule(route.params.id as string, form.value)
    router.push('/rules')
  }
  </script>
  