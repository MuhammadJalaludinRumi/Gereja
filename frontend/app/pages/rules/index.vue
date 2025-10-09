<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRules } from '~/composables/useRules'

const rules = ref<any[]>([])
const { getRules, deleteRule } = useRules()
const tableHeaders = ['Role', 'ACL', 'Permission', 'Created At']

// ambil data dari API
const fetchData = async () => {
  try {
    const data = await getRules()
    rules.value = Array.isArray(data) ? data : []
  } catch (error) {
    console.error('Gagal memuat data rules:', error)
  }
}

// hapus rule langsung tanpa modal
const handleDelete = async (id: number) => {
  if (!confirm('Apakah kamu yakin ingin menghapus rule ini?')) return

  try {
    await deleteRule(id)
    await fetchData()
  } catch (error) {
    console.error('Gagal menghapus rule:', error)
  }
}

const formatDate = (date: string) => (date ? date.split('T')[0] : '-')

// load data saat komponen dipasang
onMounted(fetchData)
</script>

<template>
  <div class="p-6 w-full overflow-hidden" style="color: var(--ui-text); background: var(--ui-bg);">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold" style="color: var(--ui-text-highlighted);">
        Daftar Rules
      </h1>
      <UButton
        to="/rules/create"
        icon="i-heroicons-plus-circle"
        size="md"
        color="primary"
        label="Tambah Rule"
      />
    </div>

    <!-- Table -->
    <UCard :ui="{ body: { padding: '' } }" class="relative z-0 overflow-hidden">
      <div class="overflow-x-auto w-full">
        <table class="min-w-full table-auto border-collapse">
          <thead>
            <tr>
              <th
                v-for="head in tableHeaders"
                :key="head"
                class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap"
              >
                {{ head }}
              </th>
              <th class="px-3 py-3 text-center text-xs font-semibold uppercase whitespace-nowrap">
                Aksi
              </th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="rule in rules" :key="rule.id" class="transition-colors hovered-row">
              <td class="px-3 py-3 text-sm font-medium whitespace-nowrap">
                {{ rule.role?.name || '-' }}
              </td>
              <td class="px-3 py-3 text-sm">{{ rule.acl?.name || '-' }}</td>
              <td class="px-3 py-3 text-sm">
                <span :class="rule.permission ? 'text-green-600' : 'text-red-600'">
                  {{ rule.permission ? 'Yes' : 'No' }}
                </span>
              </td>
              <td class="px-3 py-3 text-sm whitespace-nowrap">{{ formatDate(rule.created_at) }}</td>

              <td class="px-3 py-3 text-sm whitespace-nowrap text-center">
                <div class="flex justify-center gap-2">
                  <UButton
                    :to="`/rules/${rule.id}`"
                    icon="i-heroicons-pencil-square"
                    size="xs"
                    color="blue"
                    variant="soft"
                    label="Edit"
                  />
                  <UButton
                    @click.stop="handleDelete(rule.id)"
                    icon="i-heroicons-trash"
                    size="xs"
                    color="red"
                    variant="soft"
                    label="Delete"
                  />
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </UCard>
  </div>
</template>
