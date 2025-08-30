import { reactive, readonly } from 'vue';

type ToastColor = 'info' | 'success' | 'warning' | 'error';

interface ToastState {
    visible: boolean;
    message: string;
    color: ToastColor;
    timeout: number;
}

interface ShowToastOptions {
    message: string;
    color?: ToastColor;
    timeout?: number;
}

const toastState = reactive<ToastState>({
    visible: false,
    message: '',
    color: 'info',
    timeout: 2000,
});

const showToast = ({ message, color = 'info', timeout = 2000 }: ShowToastOptions): void => {
    toastState.message = message;
    toastState.color = color;
    toastState.timeout = timeout;
    toastState.visible = true;

    setTimeout(() => {
        toastState.visible = false;
    }, timeout);
};

export const useToast = () => {
    return {
        toastState: readonly(toastState),
        showToast,
    };
};