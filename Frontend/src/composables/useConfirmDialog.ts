import { reactive, readonly } from 'vue';
import type { ConfirmDialogOptions, ConfirmDialogState } from '@/types/ConfirmDialog';

export interface ConfirmDialogOptions {
    title?: string;
    message?: string;
}

export interface ConfirmDialogState {
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

const open = (options: ConfirmDialogOptions = {}): Promise<boolean> => {
    state.title = options.title || 'Confirmação';
    state.message = options.message || 'Você tem certeza?';
    state.visible = true;

    return new Promise<boolean>((resolve) => {
        state.resolveCallback = resolve;
    });
};

const confirm = (): void => {
    state.visible = false;
    if (state.resolveCallback) {
        state.resolveCallback(true);
        state.resolveCallback = null;
    }
};

const cancel = (): void => {
    state.visible = false;
    if (state.resolveCallback) {
        state.resolveCallback(false);
        state.resolveCallback = null;
    }
};

export function useConfirmDialog() {
    return {
        state: readonly(state),
        open,
        confirm,
        cancel,
    } as const;
}