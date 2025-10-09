<script setup lang="ts">
const emit = defineEmits<{
  toggleSidebar: []
}>()

const route = useRoute()
const router = useRouter()

const groups = computed(() => [
  {
    id: 'links',
    label: 'Go to',
    items: [
      { label: 'Home', icon: 'i-lucide-house', to: '/' },
      { label: 'Acls', icon: 'i-lucide-shield-check', to: '/acls' },
      { label: 'City', icon: 'i-lucide-map-pin', to: '/city' },
      { label: 'Invoice', icon: 'i-lucide-receipt', to: '/invoices' },
      { label: 'License', icon: 'i-lucide-badge-check', to: '/licenses' },
      { label: 'News', icon: 'i-lucide-newspaper', to: '/news' },
      { label: 'Organization License', icon: 'i-lucide-file-badge', to: '/organizationLicense' },
      { label: 'Organizations', icon: 'i-lucide-users', to: '/organizations' },
      { label: 'Provinces', icon: 'i-lucide-map', to: '/province' },
      { label: 'Role management', icon: 'i-lucide-crown', to: '/roles' },
      { label: 'Rules', icon: 'i-lucide-scale', to: '/rules' },
      { label: 'User Authorities', icon: 'i-lucide-shield', to: '/user-authorities' },
      { label: 'User Management', icon: 'i-lucide-user-cog', to: '/users' },
      { label: 'Yayasan', icon: 'i-lucide-building-2', to: '/groups' }
    ]
  },
  {
    id: 'code',
    label: 'Code',
    items: [
      {
        id: 'source',
        label: 'View page source',
        icon: 'i-simple-icons-github',
        to: `https://github.com/nuxt-ui-templates/dashboard/blob/main/app/pages${route.path === '/' ? '/index' : route.path}.vue`,
        target: '_blank'
      }
    ]
  }
])

const profileItems = [
  [{
    label: 'Pengaturan',
    icon: 'i-lucide-settings',
    click: () => {
      router.push('/profile')
    }
  }],
  [{
    label: 'Logout',
    icon: 'i-lucide-log-out',
    click: () => {
      // Tambahkan logic logout di sini
      console.log('Logout clicked')
      // Contoh: router.push('/login')
    }
  }]
]
</script>

<template>
  <!-- Navbar fixed di atas, pakai warna dari var(--ui-bg) dan var(--ui-border) -->
  <UDashboardNavbar class="fixed top-0 left-0 right-0 z-50 border-b"
    style="background: var(--ui-bg); border-color: var(--ui-border); color: var(--ui-text);">
    <!-- Kiri -->
    <template #left>
      <UButton icon="i-lucide-menu" color="gray" variant="ghost" @click="emit('toggleSidebar')" />
    </template>

    <!-- Tengah (search bar) -->
    <template #center>
      <UDashboardSearch :groups="groups" />
    </template>

    <!-- Kanan -->
    <template #right>
      <div class="flex items-center gap-2">
        <!-- Notifikasi -->
        <UTooltip text="Notifications">
          <UButton icon="i-lucide-bell" color="gray" variant="ghost" />
        </UTooltip>

        <UDropdown :popper="{ placement: 'bottom-end', strategy: 'fixed' }">
          <!-- Trigger -->
          <template #trigger="{ open }">
            <UButton icon="i-lucide-user-circle" color="gray" variant="ghost" class="!text-gray-700 dark:!text-gray-200"
              aria-label="Profile menu" />
          </template>
          <!-- Isi dropdown -->
          <template #items>
            <UDropdownItem icon="i-lucide-settings" label="Pengaturan" @click="router.push('/profile')" />
            <UDropdownItem icon="i-lucide-log-out" label="Logout" @click="console.log('Logout clicked')" />
          </template>
        </UDropdown>
      </div>
    </template>
  </UDashboardNavbar>

  <!-- Spacer biar konten gak ketiban navbar -->
  <div class="h-[64px]"></div>
</template>
