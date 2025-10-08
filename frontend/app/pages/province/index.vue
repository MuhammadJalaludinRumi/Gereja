<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

const api = 'http://localhost:8000/api/province'

// State
const provinces = ref<any[]>([])
const name = ref('')
const search = ref('')

// Modal edit
const showEditModal = ref(false)
const editProvince = ref({ id: null, name: '' })

// Modal delete
const showDeleteModal = ref(false)
const deleteTarget = ref({ id: null, name: '' })

// Fetch data
const fetchProvinces = async () => {
  provinces.value = await $fetch(api)
}

// Filter untuk live search
const filteredProvinces = computed(() => {
  if (!search.value) return provinces.value
  const keyword = search.value.toLowerCase()
  return provinces.value.filter(p => p.name.toLowerCase().includes(keyword))
})

// Tambah province
const addProvince = async () => {
  if (!name.value) return
  await $fetch(api, {
    method: 'POST',
    body: { name: name.value }
  })
  name.value = ''
  fetchProvinces()
}

// Buka modal edit
const openEditModal = (province: any) => {
  editProvince.value = { ...province }
  showEditModal.value = true
}

// Simpan update
const saveUpdate = async () => {
  await $fetch(`${api}/${editProvince.value.id}`, {
    method: 'PUT',
    body: { name: editProvince.value.name }
  })
  showEditModal.value = false
  fetchProvinces()
}

// Buka modal delete
const openDeleteModal = (province: any) => {
  deleteTarget.value = { ...province }
  showDeleteModal.value = true
}

// Konfirmasi delete
const confirmDelete = async () => {
  await $fetch(`${api}/${deleteTarget.value.id}`, { method: 'DELETE' })
  showDeleteModal.value = false
  fetchProvinces()
}

onMounted(fetchProvinces)
</script>

<template>
  <div class="p-6 w-full overflow-hidden" style="background: var(--ui-bg); color: var(--ui-text);">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold" style="color: var(--ui-text-highlighted);">
        CRUD Province
      </h1>
    </div>

    <!-- Form Tambah -->
    <UCard class="mb-6" :ui="{ body: { padding: 'p-4' } }">
      <div class="flex flex-col md:flex-row gap-3 items-center">
        <input
          v-model="search"
          placeholder="Cari province..."
          class="flex-1 rounded-lg border px-3 py-2 text-sm"
          style="background: var(--ui-bg); border-color: var(--ui-border); color: var(--ui-text);"
        />

        <input
          v-model="name"
          placeholder="Nama Province"
          class="flex-1 rounded-lg border px-3 py-2 text-sm"
          style="background: var(--ui-bg); border-color: var(--ui-border); color: var(--ui-text);"
        />

        <UButton color="primary" icon="i-heroicons-plus-circle" label="Tambah" @click="addProvince" />
      </div>
    </UCard>

    <!-- Table Province -->
    <UCard :ui="{ body: { padding: '' } }" class="relative overflow-hidden">
      <div class="overflow-x-auto w-full">
        <table class="min-w-full table-auto border-collapse" style="color: var(--ui-text);">
          <thead style="background: var(--ui-bg-muted); border-bottom: 1px solid var(--ui-border);">
            <tr>
              <th class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap" style="color: var(--ui-text-highlighted);">
                ID
              </th>
              <th class="px-3 py-3 text-left text-xs font-semibold uppercase whitespace-nowrap" style="color: var(--ui-text-highlighted);">
                Province
              </th>
              <th class="px-3 py-3 text-center text-xs font-semibold uppercase whitespace-nowrap" style="color: var(--ui-text-highlighted);">
                Action
              </th>
            </tr>
          </thead>

          <tbody style="background: var(--ui-bg);">
            <tr
              v-for="prov in filteredProvinces"
              :key="prov.id"
              class="transition-colors"
              :style="{ borderBottom: '1px solid var(--ui-border)' }"
            >
              <td class="px-3 py-3 text-sm whitespace-nowrap">{{ prov.id }}</td>
              <td class="px-3 py-3 text-sm whitespace-nowrap" style="color: var(--ui-text-highlighted);">
                {{ prov.name }}
              </td>
              <td class="px-3 py-3 text-center whitespace-nowrap flex justify-center gap-2">
                <UButton color="yellow" variant="soft" size="xs" icon="i-heroicons-pencil" label="Edit"
                  @click="openEditModal(prov)" />
                <UButton color="red" variant="soft" size="xs" icon="i-heroicons-trash" label="Hapus"
                  @click="openDeleteModal(prov)" />
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </UCard>

    <!-- Modal Edit -->
    <Teleport to="body">
      <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-96">
          <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Edit Province</h2>
          <input v-model="editProvince.name" class="border px-3 py-2 w-full mb-4 rounded"
            style="background: var(--ui-bg); border-color: var(--ui-border); color: var(--ui-text);" />
          <div class="flex justify-end">
            <UButton color="gray" label="Batal" @click="showEditModal = false" class="mr-2" />
            <UButton color="green" label="Simpan" @click="saveUpdate" />
          </div>
        </div>
      </div>
    </Teleport>

    <!-- Modal Delete -->
    <Teleport to="body">
      <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-96">
          <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Konfirmasi Hapus</h2>
          <p class="text-gray-800 dark:text-gray-300 mb-4">
            Yakin mau hapus <strong>{{ deleteTarget.name }}</strong>?
          </p>
          <div class="flex justify-end">
            <UButton color="gray" label="Batal" @click="showDeleteModal = false" class="mr-2" />
            <UButton color="red" label="Hapus" @click="confirmDelete" />
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>

<style scoped>
tr:hover {
  background: var(--ui-bg-muted) !important;
  transition: background 0.2s ease;
}
</style>
