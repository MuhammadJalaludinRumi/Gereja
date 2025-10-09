<script setup lang="ts">
const toast = useToast()
const sidebarOpen = ref(false)
const route = useRoute()

watch(() => route.path, () => {
  sidebarOpen.value = false
})

const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value
}

onMounted(async () => {
  const cookie = useCookie('cookie-consent')
  if (cookie.value === 'accepted') return

  toast.add({
    title: 'We use first-party cookies to enhance your experience on our website.',
    duration: 0,
    close: false,
    actions: [
      {
        label: 'Accept',
        color: 'neutral',
        variant: 'outline',
        onClick: () => {
          cookie.value = 'accepted'
        }
      },
      {
        label: 'Opt out',
        color: 'neutral',
        variant: 'ghost'
      }
    ]
  })
})
</script>

<template>
  <UDashboardGroup
    unit="rem"
    class="min-h-screen bg-[var(--ui-bg)] text-[var(--ui-text)]"
  >
    <!-- Navbar -->
    <AppNavbar
      class="sticky top-0 z-50 border-b"
      style="background: var(--ui-bg); border-color: var(--ui-border);"
      @toggle-sidebar="toggleSidebar"
    />

    <!-- Sidebar -->
    <AppSidebar v-model="sidebarOpen" />

    <!-- Konten utama (otomatis di bawah navbar tanpa resize) -->
    <div class="flex-1 overflow-auto mt-15">
      <slot />
    </div>

    <NotificationsSlideover />
  </UDashboardGroup>
</template>
