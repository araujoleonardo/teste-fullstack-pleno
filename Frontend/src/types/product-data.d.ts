export interface Product {
  id?: number
  name?: string
  price?: string
  description?: string
  createdAt?: string
}

export interface ProductForm {
  id?: number
  name?: string
  price?: string
  description?: string
}

export interface PropsProduct {
  visible: boolean
  tipoForm?: string
  product?: Product
  userId?: number
  handleClose: () => void
}
