<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useRules } from '~/composables/useRules'
import { useRoles } from '~/composables/useRoles'
import { useAcls } from '~/composables/useAcls'

const router = useRouter()
const { createRule } = useRules()
const { roles, fetchRoles } = useRoles()
const { acls, fetchAll } = useAcls()

const form = ref({
  role_id: null,
  acl_id: null,
  permission: true,
})

const saving = ref(false)
const error = ref<string | null>(null)

onMounted(async () => {
  await fetchRoles()
  await fetchAll()
})

const save = async () => {
  if (!form.value.role_id || !form.value.acl_id) {
    error.value = 'Role dan ACL wajib dipilih'
    return
  }

  saving.value = true
  try {
    await createRule(form.value)
    router.push('/rules')
  } catch (err) {
    error.value = 'Gagal menyimpan rule'
  } finally {
    saving.value = false
  }
}
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Tambah Rule Baru</h1>

    <form @submit.prevent="save">
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
        <UButton type="submit" :loading="saving" color="primary" label="Simpan" />
        <UButton color="gray" variant="soft" label="Batal" @click="router.push('/rules')" />
      </div>

      <p v-if="error" class="text-red-500 mt-3">{{ error }}</p>
    </form>
  </div>
</template>
