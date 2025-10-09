<script setup lang="ts">
import { reactive, ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useUserAuthorities } from '~/composables/useUserAuthorities'

const router = useRouter()
const { create } = useUserAuthorities()

const form = reactive({
  user_id: null as number | null,
  role_id: null as number | null
})

const users = ref<{ id: number; username: string }[]>([])
const roles = ref<{ id: number; name: string }[]>([])
const loading = ref(true)
const saving = ref(false)
const serverError = ref<string | null>(null)

const selectedUser = computed(() => {
  return users.value.find(u => u.id === form.user_id)
})

const selectedRole = computed(() => {
  return roles.value.find(r => r.id === form.role_id)
})

onMounted(async () => {
  try {
    // ambil semua users
    const usersRes = await $fetch('http://localhost:8000/api/users')
    users.value = Array.isArray(usersRes)
      ? usersRes.sort((a, b) => a.username.localeCompare(b.username))
      : []

    // ambil semua roles
    const rolesRes = await $fetch('http://localhost:8000/api/roles')
    roles.value = Array.isArray(rolesRes)
      ? rolesRes.sort((a, b) => a.name.localeCompare(b.name))
      : []
  } catch (err: any) {
    console.error(err)
    serverError.value = err.message || 'Gagal memuat data'
  } finally {
    loading.value = false
  }
})

const save = async () => {
  serverError.value = null

  // basic client validation
  if (!form.user_id || !form.role_id) {
    serverError.value = 'User dan Role wajib dipilih.'
    return
  }

  saving.value = true
  try {
    await create({
      user_id: Number(form.user_id),
      role_id: Number(form.role_id)
    })
    await router.push('/user-authorities')
  } catch (err: any) {
    console.error('create failed', err)
    serverError.value = err.message || 'Server error'
  } finally {
    saving.value = false
  }
}
</script>

<template>
  <div class="p-6 w-full overflow-hidden" style="background: var(--ui-bg); color: var(--ui-text);">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold" style="color: var(--ui-text-highlighted);">
        Tambah User Authority
      </h1>
      <UButton
        to="/user-authorities"
        icon="i-heroicons-arrow-left"
        color="gray"
        variant="soft"
        label="Back"
      />
    </div>

    <!-- Loading -->
    <div v-if="loading" class="mb-4 text-sm text-gray-400">Loading...</div>

    <!-- Form Card -->
    <UCard v-else :ui="{ body: { padding: 'p-6' } }">
      <form @submit.prevent="save" class="space-y-6">
        <!-- User Select -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            User
          </label>
          <select
            v-model.number="form.user_id"
            required
            class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
          >
            <option value="" disabled>Pilih User</option>
            <option
              v-for="user in users"
              :key="user.id"
              :value="user.id"
            >
              {{ user.username }}
            </option>
          </select>
        </div>

        <!-- Role Select -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            Role
          </label>
          <select
            v-model.number="form.role_id"
            required
            class="w-full px-3 py-2 text-sm rounded-lg transition-colors"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
          >
            <option value="" disabled>Pilih Role</option>
            <option
              v-for="role in roles"
              :key="role.id"
              :value="role.id"
            >
              {{ role.name }}
            </option>
          </select>
        </div>

        <!-- Selected Items Preview -->
        <div v-if="selectedUser || selectedRole" class="flex flex-wrap items-center gap-2">
          <span class="text-sm" style="color: var(--ui-text-dimmed);">Selected:</span>
          <UBadge v-if="selectedUser" color="blue" variant="soft">
            {{ selectedUser.username }}
          </UBadge>
          <UBadge v-if="selectedRole" color="primary" variant="soft">
            {{ selectedRole.name }}
          </UBadge>
        </div>

        <!-- Error Alert -->
        <div
          v-if="serverError"
          class="px-4 py-3 rounded-lg text-sm"
          style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444;"
        >
          {{ serverError }}
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3 pt-2">
          <UButton
            type="submit"
            :loading="saving"
            :disabled="saving"
            color="primary"
            icon="i-heroicons-check-circle"
            :label="saving ? 'Saving...' : 'Save'"
          />
          <UButton
            color="gray"
            variant="soft"
            icon="i-heroicons-x-mark"
            label="Cancel"
            @click="router.push('/user-authorities')"
          />
        </div>
      </form>
    </UCard>
  </div>
</template>

<style scoped>
select:focus {
  outline: none;
  border-color: var(--ui-primary);
}
</style>
