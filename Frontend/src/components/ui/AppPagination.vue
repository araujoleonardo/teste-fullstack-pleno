<script setup lang="ts">
import { PaginatedDataModel } from "@/models/PaginatedDataModel";

const emit = defineEmits<{
  (e: 'page', data: string): void
  (e: 'perPage', value: number): void
}>()

defineProps({
  data: {
    type: Object as () => PaginatedDataModel<any>,
    default: () => new PaginatedDataModel<any>(),
  },
})

const emviaEmit = (url: string | undefined): void => {
  if (!url) {
    return
  }
  emit('page', url)
}

const handlePerPage = (event: Event): void => {
  const select = event.target as HTMLSelectElement
  const value = parseInt(select.value, 10)
  emit('perPage', value)
}

const isPrevious = (label: string): boolean => {
  return label.includes('&laquo;') || label.toLowerCase().includes('previous')
}

const isNext = (label: string): boolean => {
  return label.includes('&raquo;') || label.toLowerCase().includes('next')
}
</script>

<template>
  <div>
    <!-- Container principal com layout flexível -->
    <v-row align="center" justify="space-between" class="mt-4 w-100">
      <!-- Texto informativo -->
      <v-col cols="12" md="auto" v-if="data.total" class="text-center text-md-left">
        <div class="text-caption text-secondary">
          Mostrando {{ data.to }} de {{ data.total }} itens.
        </div>
      </v-col>

      <!-- Botões de paginação e seletor de itens por página -->
      <v-col cols="12" md="auto" class="d-flex flex-column flex-md-row align-center justify-space-between w-100 md-w-auto overflow-x-auto text-caption">
        <!-- Seletor de quantidade de itens por página -->
        <v-col cols="12" md="auto" class="text-center mr-3">
          <span class="text-secondary mr-2">Por página:</span>
          <v-select
              v-model="data.perPage"
              :items="[10, 20, 50]"
              density="compact"
              variant="outlined"
              hide-details
              @update:model-value="handlePerPage"
              class="text-secondary"
          ></v-select>
        </v-col>

        <!-- Botões de paginação -->
        <div class="d-flex align-center">
          <v-btn
              v-for="(link, k) in data.links"
              :key="k"
              :color="link.active ? 'primary' : 'secondary'"
              :variant="link.active ? 'tonal' : 'text'"
              size="small"
              class="mx-1"
              :disabled="!link.url"
              @click="emviaEmit(link.url)"
              :prepend-icon="isPrevious(link.label) ? 'mdi-chevron-left' : isNext(link.label) ? 'mdi-chevron-right' : ''"
              v-html="isPrevious(link.label) || isNext(link.label) ? '' : link.label"
          ></v-btn>
        </div>
      </v-col>
    </v-row>
  </div>
</template>
