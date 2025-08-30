<script setup lang="ts">
import {useConfirmDialog} from "@/composables/useConfirmDialog.ts";

const { state, confirm, cancel } = useConfirmDialog();
</script>

<template>
  <v-dialog v-model="state.visible" persistent max-width="400px">
    <v-card>
      <v-card-title class="text-h6">{{ state.title }}</v-card-title>
      <v-card-text>{{ state.message }}</v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="red" variant="text" @click="cancel">Não</v-btn>
        <v-btn color="green" variant="text" @click="confirm">Sim</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

