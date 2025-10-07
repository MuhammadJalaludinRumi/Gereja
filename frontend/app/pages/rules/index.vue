<template>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">Daftar Rules</h1>
  
      <div class="mb-4">
        <NuxtLink
          to="/rules/create"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
        >
          + Tambah Rule
        </NuxtLink>
      </div>
  
      <table class="min-w-full border">
        <thead>
          <tr class="bg-gray-100">
            <th class="border px-4 py-2">ID</th>
            <th class="border px-4 py-2">Role</th>
            <th class="border px-4 py-2">ACL</th>
            <th class="border px-4 py-2">Permission</th>
            <th class="border px-4 py-2">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="rule in rules" :key="rule.id">
            <td class="border px-4 py-2">{{ rule.id }}</td>
            <td class="border px-4 py-2">{{ rule.role }}</td>
            <td class="border px-4 py-2">{{ rule.acl }}</td>
            <td class="border px-4 py-2">
              <span
                :class="rule.permission ? 'text-green-600' : 'text-red-600'"
              >
                {{ rule.permission ? 'Yes' : 'No' }}
              </span>
            </td>
            <td class="border px-4 py-2 space-x-2">
              <NuxtLink
                :to="`/rules/${rule.id}`"
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded"
              >
                Edit
              </NuxtLink>
              <button
                @click="removeRule(rule.id)"
                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded"
              >
                Hapus
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script setup lang="ts">
  import { useRules } from '~/composables/useRules'
  
  const { getRules, deleteRule } = useRules()
  const rules = ref<any[]>([])
  
  onMounted(async () => {
    rules.value = await getRules()
  })
  
  const removeRule = async (id: number) => {
    if (confirm('Yakin ingin menghapus rule ini?')) {
      await deleteRule(id)
      rules.value = await getRules()
    }
  }
  </script>
  