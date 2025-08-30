<script setup lang="ts">
import {useToast} from "@/composables/useToast.ts";
import { Info, CircleAlert, Bug, CircleCheck } from 'lucide-vue-next';

const { toastState } = useToast();
</script>

<template>
  <v-snackbar
    v-model="toastState.visible"
    :timeout="toastState.timeout"
    :color="toastState.color"
    height="60"
    dark
  >
    <v-icon v-if="toastState.color === 'info'"> <Info /> </v-icon>
    <v-icon v-else-if="toastState.color === 'warning'"> <CircleAlert /> </v-icon>
    <v-icon v-else-if="toastState.color === 'error'"> <Bug /> </v-icon>
    <v-icon v-else> <CircleCheck /> </v-icon>

    <span class="ms-6">{{ toastState.message }}</span>
  </v-snackbar>
</template>

