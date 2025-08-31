import { ref, reactive, watch } from 'vue'
import type {PropsProduct, ProductForm} from "@/types/product-data";
import type {ValidationErrors} from "@/types/error-data";
import api from "@/services/useApi.ts";
import {ProductModel} from "@/models/ProductModel.ts";
import {useToast} from "@/composables/useToast.ts";

export default function useProductForm(props: PropsProduct, emit: (event: 'reload') => void) {
    const open = ref<boolean>(false);
    const { showToast } = useToast();
    const title = ref<string>('Título');
    const btnTitulo = ref<string>('Salvar');
    const urlSubmit = ref<string>('');
    const method = ref<"post"|"put">("post");
    const loading = ref<boolean>(false);
    const validate = ref<ValidationErrors>({});

    const formData = reactive<ProductForm>(new ProductModel());

    const resetForm = (): void => {
        Object.assign(formData, new ProductModel());
    }

    const handleSubmit = async (): Promise<void> => {
        loading.value = true
        try {
            const response = await api[method.value](urlSubmit.value, formData)
            showToast({message: response?.data?.success || 'Dados cadastrados com sucesso!', color: 'success'});
            emit('reload')
            props.handleClose()
        } catch (error: any) {
            validate.value = error.response?.data?.errors || {}
            if (!validate.value || Object.keys(validate.value).length === 0) {
                showToast({message: 'Erro ao salvar, tente mais tarde!', color: 'error'});
            }
        } finally {
            loading.value = false
        }
    }

    const close = () => {
        resetForm();
        props.handleClose();
    }

    watch(
        () => props.visible,
        (newVisible: boolean) => {
            open.value = newVisible;
            if (newVisible) {
                validate.value = {};
                if (props.tipoForm === 'novo') {
                    title.value = 'Novo Produto'
                    btnTitulo.value = 'Salvar'
                    urlSubmit.value = '/product/create/'+props.userId
                    method.value = 'post'
                    resetForm();
                } else if (props.tipoForm === 'update' && props.product) {
                    title.value = 'Editar Produto'
                    btnTitulo.value = 'Atualizar'
                    urlSubmit.value = '/product/update'
                    method.value = 'put'
                    Object.assign(formData, {
                        id: props.product.id,
                        name: props.product.name,
                        price: props.product.price,
                        description: props.product.description,
                    })
                }
            }
        }
    )

    watch(open, (val) => {
        if (!val) {
            props.handleClose()
        }
    })

    return {
        open,
        title,
        btnTitulo,
        urlSubmit,
        loading,
        validate,
        formData,
        close,
        handleSubmit,
    }
}