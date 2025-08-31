<script setup lang="ts">
import ErrorLabel from "@/components/ui/ErrorLabel.vue";
import type {Product, PropsProduct} from "@/types/product-data";
import useProductForm from "@/composables/product/useProductForm.ts";

const props = withDefaults(defineProps<PropsProduct>(), {
  tipoForm: 'novo',
  product: () => ({} as Product),
  userId: () => ({} as number),
  visible: false,
  handleClose: () => {},
})

const emit = defineEmits<{
  (e: 'reload'): void;
}>();

const {
  title,
  btnTitulo,
  loading,
  validate,
  formData,
  handleSubmit,
  open,
  close
} = useProductForm(props, emit);
</script>

<template>
  <v-dialog
      v-model="open"
      width="auto"
  >
    <v-card width="600">
      <v-card-title>
        {{title}}
      </v-card-title>
      <v-divider></v-divider>
      <v-card-text>
        <v-row dense>
          <v-col cols="12">
            <v-text-field
                id="name"
                name="name"
                type="text"
                variant="outlined"
                label="Nome Completo"
                v-model="formData.name"
                required/>
            <error-label v-if="validate.name" :message="validate.name[0]" />
          </v-col>

          <v-col cols="12">
            <v-text-field
                id="price"
                name="price"
                type="text"
                variant="outlined"
                label="Preco"
                v-mask="['R$ ##,##', 'R$ ###,##', 'R$ ###.###,##']"
                v-model="formData.price"
                required/>
            <error-label v-if="validate.price" :message="validate.price[0]" />
          </v-col>

          <v-col cols="12">
            <v-text-field
                id="description"
                name="description"
                type="text"
                variant="outlined"
                label="Descricao"
                v-model="formData.description"
                required/>
            <error-label v-if="validate.description" :message="validate.description[0]" />
          </v-col>
        </v-row>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-btn
            text="Cancelar"
            variant="plain"
            :loading="loading"
            @click="close"
        ></v-btn>

        <v-btn
            color="primary"
            :text="btnTitulo"
            variant="tonal"
            :loading="loading"
            @click="handleSubmit"
        ></v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>