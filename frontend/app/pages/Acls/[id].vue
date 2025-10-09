<template>
  <div class="p-6 w-full overflow-hidden" style="background: var(--ui-bg); color: var(--ui-text);">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold" style="color: var(--ui-text-highlighted);">Edit ACL</h1>
      <UButton to="/acls" icon="i-heroicons-arrow-left" color="gray" variant="soft" label="Kembali" />
    </div>

    <UCard :ui="{ body: { padding: 'p-6' } }">
      <form @submit.prevent="save" class="space-y-6">
        <div>
          <label class="block mb-2 text-sm font-semibold" style="color: var(--ui-text-highlighted);">Nama ACL</label>
          <input
            v-model="form.name"
            type="text"
            required
            placeholder="Masukkan nama ACL"
            class="w-full px-3 py-2 text-sm rounded-lg border transition-colors"
            style="background: var(--ui-bg); border: 1px solid var(--ui-border); color: var(--ui-text);"
          />
        </div>

        <div class="flex items-center gap-3 pt-2">
          <UButton
            type="submit"
            color="primary"
            icon="i-heroicons-check-circle"
            label="Update ACL"
          />
          <UButton
            color="gray"
            variant="soft"
            icon="i-heroicons-x-mark"
            label="Batal"
            @click="router.push('/acls')"
          />
        </div>
      </form>
    </UCard>
  </div>
</template>

<script setup lang="ts">
import { reactive, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAcls } from '~/composables/useAcls'

const { fetchById, update, acl } = useAcls()
const route = useRoute()
const router = useRouter()
const form = reactive({ name: '' })

onMounted(async () => {
  await fetchById(Number(route.params.id))
  form.name = acl.value?.name || ''
})

const save = async () => {
  await update(Number(route.params.id), form)
  router.push('/acls')
}
</script>
