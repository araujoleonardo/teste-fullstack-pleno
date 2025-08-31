export interface User {
  id?: number
  name?: string
  email?: string
  cpf?: string
  createdAt?: string
}

export interface UserForm {
  id?: number
  name?: string
  email?: string
  cpf?: string
}

export interface PropsUser {
  visible: boolean
  tipoForm?: string
  user?: User
  handleClose: () => void
}
