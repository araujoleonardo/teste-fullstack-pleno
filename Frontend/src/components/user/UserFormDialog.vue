<script setup lang="ts">
import useUserForm from "@/composables/user/useUserForm.js";
import ErrorLabel from "@/components/ui/ErrorLabel.vue";
import type {PropsUser, User} from "@/types/user-data";

const props = withDefaults(defineProps<PropsUser>(), {
  tipoForm: 'novo',
  user: () => ({} as User),
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
} = useUserForm(props, emit);
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
                id="email"
                name="email"
                type="text"
                variant="outlined"
                label="Email"
                v-model="formData.email"
                required/>
            <error-label v-if="validate.email" :message="validate.email[0]" />
          </v-col>

          <v-col cols="12">
            <v-text-field
                id="cpf"
                name="cpf"
                type="text"
                variant="outlined"
                label="CPF"
                v-mask="['###.###.###-##']"
                v-model="formData.cpf"
                required/>
            <error-label v-if="validate.cpf" :message="validate.cpf[0]" />
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