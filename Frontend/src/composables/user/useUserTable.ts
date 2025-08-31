import {ref, reactive, onMounted, watch} from 'vue';
import {PaginatedDataModel} from "@/models/PaginatedDataModel.ts";
import api from "@/services/useApi.ts";
import type {User} from "@/types/user-data";
import type {IParams} from "@/types/pagination-data";
import {useToast} from "@/composables/useToast.ts";
import {useConfirmDialog} from "@/composables/useConfirmDialog.ts";

export default function useUserTable(baseEndpoint: string) {
  const { showToast } = useToast();
  const { open } = useConfirmDialog();
  const loading = ref(false);
  const baseUrl = ref(`${baseEndpoint}?page=1`);
  const currentPage = ref<number | undefined>(undefined);
  const openDialog = ref({ isOpen: false, tipoForm: 'novo' });
  const openShow = ref({ isOpen: false });
  const user = ref<User|undefined>(undefined);
  const debounceTimeout = ref<number|undefined>(undefined);
  const params = reactive<IParams>({
    search: undefined,
    field: undefined,
    direction: 'asc',
    perPage: 10,
  });
  const dataSet = reactive(new PaginatedDataModel<User>());

  const getData = async (url: string) => {
    loading.value = true;
    baseUrl.value = url;

    const currentUrl = `${url}&search=${params.search || ''}&field=${params.field || ''}&direction=${params.direction}&perPage=${params.perPage}`;

    try {
      const response = await api.get(currentUrl);
      Object.assign(dataSet, response.data.users);
    } catch (error) {
      showToast({message: 'Não foi possível carregar os dados!', color: 'error'});
    } finally {
      loading.value = false;
    }
  };

  const loadData = async () => {
    await getData(baseUrl.value);
  };

  const handleSort = async (field: string) => {
    if (params.field === field) {
      params.direction = params.direction === 'asc' ? 'desc' : 'asc';
    } else {
      params.field = field;
      params.direction = 'asc';
    }
    await getData(baseUrl.value);
  };

  watch(() => params.search, (newValue) => {
    if (debounceTimeout.value) {
      clearTimeout(debounceTimeout.value);
    }

    baseUrl.value = `${baseEndpoint}?page=1`;

    if (newValue && newValue.length >= 3) {
      // Aguarda 300ms
      debounceTimeout.value = setTimeout(async () => {
        await handleSearch();
      }, 300);
    } else if (newValue === '' || newValue === undefined) {
      // Limpa a busca
      handleSearch();
    }
  });

  const handleSearch = async () => {
    await getData(baseUrl.value);
  };

  const handlePerPage = async (val:number) => {
    params.perPage = val || 10;
    await getData(baseUrl.value);
  };

  const handleShow = (row:any) => {
    user.value = row;
    openShow.value.isOpen = true;
  };

  const handleOpen = () => {
    openDialog.value.tipoForm = 'novo';
    openDialog.value.isOpen = true;
  };

  const handleEdit = (row:any) => {
    openDialog.value.tipoForm = 'update';
    user.value = row;
    openDialog.value.isOpen = true;
  };

  const handleDelete = async (row: { id?:number | string }) => {
    const confirmed = await open({
      title: 'Atenção!',
      message: 'Esta ação não poderá ser desfeita. Deseja continuar?',
    });
    if (confirmed){
      try {
        const response = await api.delete(`/user/delete/${row.id}`);
        showToast({message: response?.data?.success || 'Registro excluído com sucesso!', color: 'success'});
        await getData(baseUrl.value);
      } catch (error: any) {
        showToast({message: error?.response?.data?.error || 'Não foi possível excluir o registro.', color: 'error'});
      }
    }
  };

  onMounted(async () => {
    await loadData();
  });

  return {
    loading,
    currentPage,
    dataSet,
    params,
    loadData,
    getData,
    handleSort,
    handleSearch,
    handleShow,
    handleOpen,
    handleEdit,
    handleDelete,
    handlePerPage,
    openDialog,
    openShow,
    user
  };
}
