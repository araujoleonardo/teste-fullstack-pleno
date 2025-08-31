<script setup lang="ts">
import {useToast} from "@/composables/useToast.ts";

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
    <v-icon v-if="toastState.color === 'info'"> <font-awesome-icon size="lg" :icon="['fas', 'circle-info']" /> </v-icon>
    <v-icon v-else-if="toastState.color === 'warning'"> <font-awesome-icon size="lg" :icon="['fas', 'triangle-exclamation']" /> </v-icon>
    <v-icon v-else-if="toastState.color === 'error'"> <font-awesome-icon size="lg" :icon="['fas', 'bug']" /> </v-icon>
    <v-icon v-else> <font-awesome-icon size="lg" :icon="['fas', 'circle-check']" /> </v-icon>

    <span class="ms-6">{{ toastState.message }}</span>
  </v-snackbar>
</template>

