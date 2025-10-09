<script setup lang="ts">
import type { NavigationMenuItem } from '@nuxt/ui'

const props = defineProps<{
  modelValue: boolean
}>()

const emit = defineEmits<{
  'update:modelValue': [value: boolean]
}>()

const isOpen = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
})

const links = [[
  { label: 'Home', icon: 'i-lucide-house', to: '/', onSelect: () => (isOpen.value = false) },
  { label: 'Acls', icon: 'i-lucide-shield-check', to: '/acls', onSelect: () => (isOpen.value = false) },
  { label: 'City', icon: 'i-lucide-map-pin', to: '/city', onSelect: () => (isOpen.value = false) },
  { label: 'Invoice', icon: 'i-lucide-receipt', to: '/invoices', onSelect: () => (isOpen.value = false) },
  { label: 'License', icon: 'i-lucide-badge-check', to: '/licenses', onSelect: () => (isOpen.value = false) },
  { label: 'News', icon: 'i-lucide-newspaper', to: '/news', onSelect: () => (isOpen.value = false) },
  { label: 'Organization License', icon: 'i-lucide-file-badge', to: '/organizationLicense', onSelect: () => (isOpen.value = false) },
  { label: 'Organizations', icon: 'i-lucide-users', to: '/organizations', onSelect: () => (isOpen.value = false) },
  { label: 'Provinces', icon: 'i-lucide-map', to: '/province', onSelect: () => (isOpen.value = false) },
  { label: 'Role management', icon: 'i-lucide-crown', to: '/roles', onSelect: () => (isOpen.value = false) },
  { label: 'Rules', icon: 'i-lucide-scale', to: '/rules', onSelect: () => (isOpen.value = false) },
  { label: 'User Authorities', icon: 'i-lucide-shield', to: '/user-authorities', onSelect: () => (isOpen.value = false) },
  { label: 'User Management', icon: 'i-lucide-user-cog', to: '/users', onSelect: () => (isOpen.value = false) },
  { label: 'Yayasan', icon: 'i-lucide-building-2', to: '/groups', onSelect: () => (isOpen.value = false) }
]]
</script>

<template>
  <!-- Overlay sidebar -->
  <Transition name="fade">
    <div v-if="isOpen" class="fixed inset-0 z-[9999] flex" style="background-color: rgba(0,0,0,0.5);"
      @click.self="isOpen = false">
      <UDashboardSidebar id="default" open collapsible resizable
        class="bg-[var(--ui-bg)] text-[var(--ui-text)] w-64 h-full border-r border-[var(--ui-border)] shadow-xl"
        :ui="{ footer: 'lg:border-t lg:border-default' }">
        <template #default="{ collapsed }">
          <div v-if="!collapsed" class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide">
            Navigasi
          </div>
          <UNavigationMenu :collapsed="collapsed" :items="links[0]" orientation="vertical" tooltip popover />
        </template>
        <template #footer="{ collapsed }">
          <UserMenu :collapsed="collapsed" />
        </template>
      </UDashboardSidebar>
    </div>
  </Transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
