<script setup lang="ts">
import { PaginatedDataModel } from "@/models/PaginatedDataModel";

// Define the emits with both page and perPage events
const emit = defineEmits<{
  (e: 'page', data: string): void
  (e: 'perPage', value: number): void
}>()

// Define os props explicitamente sem usar generics
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

// Handler for items per page change
const handlePerPage = (value: number): void => {
  emit('perPage', value)
}

// Função para identificar "Previous"
const isPrevious = (label: string): boolean => {
  return label.includes('&laquo;') || label.toLowerCase().includes('previous')
}

// Função para identificar "Next"
const isNext = (label: string): boolean => {
  return label.includes('&raquo;') || label.toLowerCase().includes('next')
}
</script>

<template>
  <v-row class="mt-4" align="center" justify="space-between">
    <!-- Texto informativo -->
    <v-col cols="12" md="auto" v-if="data.total">
      <v-sheet color="transparent">
        <span class="text-body-2 text-medium-emphasis">
          Mostrando {{ data.to }} de {{ data.total }} itens.
        </span>
      </v-sheet>
    </v-col>

    <!-- Controles de paginação -->
    <v-col cols="12" md="auto">
      <v-row align="center" justify="center" class="flex-nowrap">
        <!-- Seletor de quantidade de itens por página -->
        <v-col cols="auto">
          <v-row align="center" no-gutters>
            <v-col cols="auto" class="mr-2">
              <span class="text-body-2 text-medium-emphasis">Por página:</span>
            </v-col>
            <v-col cols="auto">
              <v-select
                  :model-value="data?.perPage || 10"
                  @update:model-value="handlePerPage"
                  :items="[10, 20, 50]"
                  variant="outlined"
                  density="compact"
                  hide-details
                  style="min-width: 50px;"
              ></v-select>
            </v-col>
          </v-row>
        </v-col>

        <!-- Botões de paginação -->
        <v-col cols="auto">
          <v-btn-group variant="outlined" density="compact">
            <v-btn
                v-for="(link, k) in data.links"
                :key="k"
                :variant="link.active ? 'flat' : 'outlined'"
                :color="link.active ? 'primary' : 'default'"
                size="small"
                :disabled="!link.url"
                @click="emviaEmit(link.url)"
                min-width="32"
                class="px-2"
            >
              <!-- Substituir texto pelos ícones -->
              <template v-if="isPrevious(link.label)">
                <font-awesome-icon :icon="['fas', 'chevron-left']" />
              </template>
              <template v-else-if="isNext(link.label)">
                <font-awesome-icon :icon="['fas', 'chevron-right']" />
              </template>
              <template v-else>
                <span v-html="link.label"></span>
              </template>
            </v-btn>
          </v-btn-group>
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>