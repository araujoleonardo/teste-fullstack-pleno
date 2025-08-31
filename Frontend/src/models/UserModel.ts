export class UserModel {
  id?: number;
  name?: string;
  email?: string;
  cpf?: string;

  constructor(data: Partial<UserModel> = {}) {
    this.id = data.id || undefined;
    this.name = data.name || undefined;
    this.email = data.email || undefined;
    this.cpf = data.cpf || undefined;
  }
}
