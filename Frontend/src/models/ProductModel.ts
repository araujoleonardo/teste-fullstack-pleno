export class ProductModel {
  id?: number;
  name?: string;
  price?: string;
  description?: string;

  constructor(data: Partial<ProductModel> = {}) {
    this.id = data.id || undefined;
    this.name = data.name || undefined;
    this.price = data.price || undefined;
    this.description = data.description || undefined;
  }
}
