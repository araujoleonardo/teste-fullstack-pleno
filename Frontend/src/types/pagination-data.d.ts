export interface PaginatedData<T> {
  current_page: number;
  data: T[];
  first_page_url: string;
  from?: number;
  last_page: number;
  last_page_url: string;
  links: PaginationLink[];
  next_page_url?: string;
  path: string;
  perPage: number;
  prev_page_url?: string;
  to?: number;
  total: number;
}

export interface PaginationLink {
  url?: string;
  label: string;
  active: boolean;
}

export interface IParams {
  search?: string,
  field?: string,
  direction: string,
  perPage: number
}
