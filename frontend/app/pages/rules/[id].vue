<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useRules } from '~/composables/useRules'
import { useRoles } from '~/composables/useRoles'
import { useAcls } from '~/composables/useAcls'

const route = useRoute()
const router = useRouter()
const { getRule, updateRule } = useRules()
const { roles, fetchRoles } = useRoles()
const { acls, fetchAll } = useAcls()

const form = ref({
  role_id: null,
  acl_id: null,
  permission: false,
})

const saving = ref(false)

onMounted(async () => {
  await fetchRoles()
  await fetchAll()
  const data = await getRule(route.params.id as string)
  if (data) form.value = data
})

const update = async () => {
  saving.value = true
  try {
    await updateRule(route.params.id as string, form.value)
    router.push('/rules')
  } finally {
    saving.value = false
  }
}
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Edit Rule</h1>

    <form @submit.prevent="update">
      <div class="mb-4">
        <label class="block mb-1 font-medium">Role</label>
        <select v-model="form.role_id" class="w-full p-2 border border-gray-300 rounded">
          <option disabled value="">Pilih Role</option>
          <option v-for="role in roles" :key="role.id" :value="role.id">
            {{ role.name }}
          </option>
        </select>
      </div>

      <div class="mb-4">
        <label class="block mb-1 font-medium">ACL</label>
        <select v-model="form.acl_id" class="w-full p-2 border border-gray-300 rounded">
          <option disabled value="">Pilih ACL</option>
          <option v-for="acl in acls" :key="acl.id" :value="acl.id">
            {{ acl.name }}
          </option>
        </select>
      </div>

      <div class="mb-4">
        <label class="block mb-1 font-medium">Permission</label>
        <select v-model="form.permission" class="w-full p-2 border border-gray-300 rounded">
          <option :value="true">Yes</option>
          <option :value="false">No</option>
        </select>
      </div>

      <div class="flex gap-3">
        <UButton type="submit" :loading="saving" color="primary" label="Update" />
        <UButton color="gray" variant="soft" label="Batal" @click="router.push('/rules')" />
      </div>
    </form>
  </div>
</template>
