import axios from 'axios'

// Define a base URL dinâmica
const baseURL = import.meta.env.VITE_NODE_ENV === 'production'
  ? window.location.origin
  : import.meta.env.VITE_BASE_URL

// Cria a instância do axios
const api = axios.create({
  baseURL: baseURL + '/api',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  },
  withCredentials: true //Essencial para enviar cookies automaticamente
})

export default api
