<script setup lang="ts">
import { useConfirmDialog } from "@/composables/useConfirmDialog";

const { state, confirm, cancel } = useConfirmDialog();

// Definir o nome do componente para DevTools
defineOptions({
  name: 'AppConfirmDialog'
});
</script>

<template>
  <v-dialog
      v-model="state.visible"
      persistent
      max-width="400px"
      transition="dialog-transition"
  >
    <v-card elevation="8">
      <v-card-title class="text-h6 pa-4">
        {{ state.title }}
      </v-card-title>

      <v-card-text class="pa-4 pt-0">
        {{ state.message }}
      </v-card-text>

      <v-card-actions class="pa-4 pt-0">
        <v-spacer />

        <v-btn
            color="error"
            variant="text"
            @click="cancel"
            data-testid="cancel-button"
        >
          Não
        </v-btn>

        <v-btn
            color="success"
            variant="text"
            @click="confirm"
            data-testid="confirm-button"
        >
          Sim
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<style scoped>
/* Adicionar estilos customizados se necessário */
.v-card {
  border-radius: 8px;
}
</style>