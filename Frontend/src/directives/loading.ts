export default {
  mounted(el:any, binding:any) {
    // Cria o elemento de overlay com o spinner
    const loadingOverlay = document.createElement('div')
    loadingOverlay.classList.add('loading-overlay')
    loadingOverlay.style.position = 'absolute'
    loadingOverlay.style.top = '0'
    loadingOverlay.style.borderRadius= '5px'
    loadingOverlay.style.left = '0'
    loadingOverlay.style.right = '0'
    loadingOverlay.style.bottom = '0'
    loadingOverlay.style.backgroundColor = 'rgb(0, 0, 0, 0.5)'
    loadingOverlay.style.display = 'flex'
    loadingOverlay.style.alignItems = 'center'
    loadingOverlay.style.justifyContent = 'center'
    loadingOverlay.style.zIndex = '10'

    // Adiciona o spinner SVG no overlay
    loadingOverlay.innerHTML = `
      <div>
        <div class="inline-block h-5 w-5 animate-spin rounded-full border-4 border-solid border-current border-e-transparent align-[-0.125em] text-surface motion-reduce:animate-[spin_1.5s_linear_infinite] text-primary"
              role="status">
          <span class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]" >
              Loading...
          </span>
        </div>
      </div>
    `

    el.style.position = 'relative'
    el.loadingOverlay = loadingOverlay
    if (binding.value) {
      el.appendChild(loadingOverlay)
    }
  },
  updated(el:any, binding:any) {
    if (binding.value) {
      el.appendChild(el.loadingOverlay)
    } else if (el.loadingOverlay.parentNode) {
      el.loadingOverlay.parentNode.removeChild(el.loadingOverlay)
    }
  },
  unmounted(el:any) {
    if (el.loadingOverlay.parentNode) {
      el.loadingOverlay.parentNode.removeChild(el.loadingOverlay)
    }
  }
}
