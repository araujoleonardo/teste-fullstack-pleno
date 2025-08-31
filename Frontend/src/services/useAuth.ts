import { ref, reactive } from 'vue';
import { defineStore } from 'pinia';
import router from '@/router';
import api from '@/services/useApi';
import Cookie from '@/utils/cookie';
import type { UserAuth } from '@/types/user-auth';
import type { ValidationErrors } from '@/types/error-data';

export const useAuthStore = defineStore('auth', () => {
    const token = ref<string | null>(Cookie.get('token'))

    const validate = ref<ValidationErrors>({})
    const user = reactive<UserAuth>({
        id: undefined,
        name: undefined,
        email: undefined,
        password: undefined,
        password_confirmation: undefined
    })

    function setToken(newToken: string): void {
        token.value = newToken
        Cookie.add('token', newToken, {
            seconds: 60 * 60 * 24 * 7, // 7 dias
            secure: true,
            sameSite: 'Lax',
        })
    }

    function clearToken(): void {
        token.value = null
        Cookie.remove('token')
    }

    async function register(payload: {
        name?: string
        email?: string
        cpf?: string
        password?: string
        password_confirmation?: string
    }): Promise<void> {
        try {
            const { data } = await api.post('/register', payload)

            if (data.token) {
                setToken(data.token)
                await checkUser()
            }
        } catch (error: any) {
            validate.value = error.response?.data?.errors || {}
            console.error('Erro no registro:', error?.response?.data || error.message)
            throw error
        }
    }

    async function login(email: string, password: string): Promise<void> {
        try {
            const { data } = await api.post('/login', { email, password })
            setToken(data.token)
            await checkUser()
        } catch (error: any) {
            validate.value = error.response?.data?.errors || {}
            console.error('Erro no login:', error?.response?.data || error.message)
            throw error
        }
    }

    async function logout(): Promise<void> {
        try {
            await api.post('/logout')
        } catch (error: any) {
            console.error('Erro no logout:', error?.response?.data || error.message)
        } finally {
            clear()
            window.location.reload()
            await router.push({ name: 'login' })
        }
    }

    async function validateToken(): Promise<boolean> {
        if (!token.value) return false

        try {
            await api.post('/validate-token')
            return true
        } catch (error: any) {
            console.warn('Token inválido, tentando refresh...')

            try {
                const { data } = await api.post('/refresh-token')
                setToken(data.token)
                return true
            } catch (refreshError) {
                console.error('Erro ao tentar refresh do token.')
                clear()
                return false
            }
        }
    }

    async function checkUser(): Promise<UserAuth | null> {
        if (!token.value) {
            console.warn('Nenhum token encontrado.')
            return null
        }

        try {
            const { data } = await api.get<UserAuth>('/user-auth')
            Object.assign(user, data)
            return user
        } catch (error: any) {
            console.error('Erro ao buscar usuário:', error?.response?.data || error.message)
            clear()
            return null
        }
    }

    function clear(): void {
        clearToken()
        Object.assign(user, { id: undefined, name: undefined, email: undefined })
    }

    return {
        token,
        user,
        validate,
        register,
        login,
        logout,
        validateToken,
        checkUser,
        clear
    }
})