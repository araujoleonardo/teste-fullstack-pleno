import { reactive, readonly } from 'vue';

interface OpenConfirmDialogOptions {
    title?: string;
    message?: string;
}

interface ConfirmDialogState {
    visible: boolean;
    title: string;
    message: string;
    resolveCallback: ((value: boolean) => void) | null;
}

const state = reactive<ConfirmDialogState>({
    visible: false,
    title: '',
    message: '',
    resolveCallback: null,
});

const open = (options: OpenConfirmDialogOptions): Promise<boolean> => {
    state.title = options.title || 'Confirmação';
    state.message = options.message || 'Você tem certeza?';
    state.visible = true;

    return new Promise((resolve) => {
        state.resolveCallback = resolve;
    });
};

const confirm = (): void => {
    if (state.resolveCallback) {
        state.resolveCallback(true);
        state.resolveCallback = null; // Limpa o callback
    }
    state.visible = false;
};

const cancel = (): void => {
    if (state.resolveCallback) {
        state.resolveCallback(false);
        state.resolveCallback = null;
    }
    state.visible = false;
};

export function useConfirmDialog() {
    return {
        state: readonly(state),
        open,
        confirm,
        cancel,
    };
}