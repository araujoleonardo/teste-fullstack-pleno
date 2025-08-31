import { ref, reactive, watch } from 'vue'
import type {PropsUser, UserForm} from "@/types/user-data";
import type {ValidationErrors} from "@/types/error-data";
import api from "@/services/useApi.ts";
import {UserModel} from "@/models/UserModel.ts";
import {useToast} from "@/composables/useToast.ts";

export default function useUserForm(props: PropsUser, emit: (event: 'reload') => void) {
    const open = ref<boolean>(false);
    const { showToast } = useToast();
    const title = ref<string>('Título');
    const btnTitulo = ref<string>('Salvar');
    const urlSubmit = ref<string>('');
    const method = ref<"post"|"put">("post");
    const loading = ref<boolean>(false);
    const validate = ref<ValidationErrors>({});

    const formData = reactive<UserForm>(new UserModel());

    const resetForm = (): void => {
        Object.assign(formData, new UserModel());
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
                    title.value = 'Novo Usuario'
                    btnTitulo.value = 'Salvar'
                    urlSubmit.value = '/user/create'
                    method.value = 'post'
                    resetForm();
                } else if (props.tipoForm === 'update' && props.user) {
                    title.value = 'Editar Usuario'
                    btnTitulo.value = 'Atualizar'
                    urlSubmit.value = '/user/update'
                    method.value = 'put'
                    Object.assign(formData, {
                        id: props.user.id,
                        name: props.user.name,
                        email: props.user.email,
                        cpf: props.user.cpf,
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
