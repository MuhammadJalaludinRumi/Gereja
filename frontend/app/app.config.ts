export default defineAppConfig({
  ui: {
    colors: {
      primary: 'green',
      neutral: 'zinc'
    }
  },
  
  fetch: {
    options: {
      credentials: 'include' // <--- COOKIE MODE AKTIF
    }
  }
})
