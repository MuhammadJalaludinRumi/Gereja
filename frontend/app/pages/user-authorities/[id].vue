<script setup lang="ts">
import { reactive, ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useUserAuthorities } from '~/composables/useUserAuthorities'

const route = useRoute()
const router = useRouter()
const { fetchById, update, loading } = useUserAuthorities()

const form = reactive({
  user_id: null as number | null,
  username: '' as string,
  role_id: null as number | null
})

const roles = ref<{ id: number; name: string }[]>([])
const saving = ref(false)
const serverError = ref<string | null>(null)

const selectedRole = computed(() => {
  return roles.value.find(r => r.id === form.role_id)
})

onMounted(async () => {
  try {
    const id = Number(route.params.id)
    const data = await fetchById(id)
    if (!data) throw new Error('Data user authority tidak ditemukan.')

    // ambil data user langsung dari backend (tanpa composable)
    const userRes = await $fetch(`http://localhost:8000/api/users/${data.user_id}`)
    form.user_id = data.user_id
    form.username = userRes?.username || `User #${data.user_id}`
    form.role_id = data.role_id

    // ambil semua role untuk combo box
    const roleRes = await $fetch(`http://localhost:8000/api/roles`)
    roles.value = Array.isArray(roleRes)
      ? roleRes.sort((a, b) => a.name.localeCompare(b.name)) // urutin a-z
      : []
  } catch (err: any) {
    console.error(err)
    serverError.value = err.message || 'Gagal memuat data'
  }
})

const updateData = async () => {
  serverError.value = null
  const id = Number(route.params.id)

  if (!form.role_id) {
    serverError.value = 'Role wajib dipilih.'
    return
  }

  saving.value = true
  try {
    await update(id, {
      user_id: Number(form.user_id),
      role_id: Number(form.role_id)
    })
    router.push('/user-authorities')
  } catch (err: any) {
    console.error('update error', err)
    serverError.value = err.message || 'Gagal update data.'
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
        Edit User Authority
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
      <form @submit.prevent="updateData" class="space-y-6">
        <!-- Username Field -->
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">
            User
          </label>
          <input
            v-model="form.username"
            type="text"
            readonly
            class="w-full px-3 py-2 text-sm rounded-lg cursor-not-allowed"
            style="background: var(--ui-bg-muted); border: 1px solid var(--ui-border); color: var(--ui-text-dimmed);"
          />
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

        <!-- Selected Role Badge -->
        <div v-if="selectedRole" class="flex items-center gap-2">
          <span class="text-sm" style="color: var(--ui-text-dimmed);">Selected:</span>
          <UBadge color="primary" variant="soft">
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
            :label="saving ? 'Updating...' : 'Update'"
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

    <!-- Info Card -->
    <UCard class="mt-6" :ui="{ body: { padding: 'p-4' } }">
      <div class="flex items-start gap-3">
        <div class="text-sm" style="color: var(--ui-text-dimmed);">
          <p class="font-semibold mb-1" style="color: var(--ui-text);">Note:</p>
          <p>Perubahan role akan langsung mempengaruhi hak akses user di sistem.</p>
        </div>
      </div>
    </UCard>
  </div>
</template>

<style scoped>
select:focus {
  outline: none;
  border-color: var(--ui-primary);
}

input:focus {
  outline: none;
  border-color: var(--ui-border);
}
</style>
