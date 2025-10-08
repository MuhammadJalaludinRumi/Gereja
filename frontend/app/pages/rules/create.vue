<template>
    <div class="p-6 max-w-lg mx-auto">
      <h1 class="text-2xl font-bold mb-4">Tambah Rule Baru</h1>

      <form @submit.prevent="save">
        <div class="mb-4">
          <label class="block mb-1 font-medium">Role</label>
          <input
            v-model.number="form.role_id"
            type="number"
            class="w-full p-2 border border-gray-300 rounded"
            required
          />
        </div>

        <div class="mb-4">
          <label class="block mb-1 font-medium">ACL</label>
          <input
            v-model.number="form.acl_id"
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
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
        >
          Simpan
        </button>
      </form>
    </div>
  </template>

  <script setup lang="ts">
  import { useRouter } from 'vue-router'
  import { useRules } from '~/composables/useRules'

  const router = useRouter()
  const { createRule } = useRules()

  const form = ref({
    role_id: 0,
    acl_id: 0,
    permission: false
  })

  const save = async () => {
    await createRule(form.value)
    router.push('/rules')
  }
  </script>
