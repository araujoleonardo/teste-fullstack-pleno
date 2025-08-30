import type {PaginatedData, PaginationLink} from "@/types/pagination-data";


export class PaginatedDataModel<T> implements PaginatedData<T> {
  current_page: number = 1;
  data: T[] = [];
  first_page_url: string = "";
  from: number | undefined = undefined;
  last_page: number = 1;
  last_page_url: string = "";
  links: PaginationLink[] = [];
  next_page_url: string | undefined = undefined;
  path: string = "";
  perPage: number = 10;
  prev_page_url: string | undefined = undefined;
  to: number | undefined = undefined;
  total: number = 0;

  constructor(init?: Partial<PaginatedData<T>>) {
    Object.assign(this, init);
  }
}
