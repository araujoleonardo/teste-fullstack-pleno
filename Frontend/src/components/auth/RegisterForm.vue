<script setup lang="ts">
import {useAuthStore} from "@/services/useAuth.ts";
import {useRouter} from "vue-router";
import {reactive, ref} from "vue";
import type {UserAuth} from "@/types/user-auth";
import ErrorLabel from "@/components/ui/ErrorLabel.vue";

const router = useRouter();
const auth = useAuthStore();
const isLoading = ref(false);
const errorMessage = ref<string | undefined>(undefined);
const user = reactive<UserAuth>({
  name: undefined,
  email: undefined,
  cpf: undefined,
  password: undefined,
  password_confirmation: undefined,
});

const handleRegister = async () => {
  try {
    isLoading.value = true;
    errorMessage.value = undefined;

    await auth.register(user);

    router.push('/');
  } catch (error) {
    errorMessage.value = 'Falha no login. Verifique suas credenciais.';
  } finally {
    isLoading.value = false;
  }
};

const handleRedirect = () => {
  router.push('/register');
}
</script>

<template>
  <v-container class="d-flex justify-center align-center" style="height: 100vh;">
    <v-card class="pa-4" style="width: 25rem;">
      <v-card-title class="justify-center">Registrar-se</v-card-title>
      <v-card-text>
        <v-form ref="loginForm">
          <v-col cols="12">
            <v-text-field
                id="name"
                name="name"
                type="name"
                variant="outlined"
                label="Nome"
                v-model="user.name"
                required/>
            <error-label v-if="auth.validate.name" :message="auth.validate.name[0]" />
          </v-col>

          <v-col cols="12">
            <v-text-field
                id="email"
                name="email"
                type="email"
                variant="outlined"
                label="Email"
                v-model="user.email"
                required/>
            <error-label v-if="auth.validate.email" :message="auth.validate.email[0]" />
          </v-col>

          <v-col cols="12">
            <v-text-field
                id="cpf"
                name="cpf"
                type="cpf"
                variant="outlined"
                label="Cpf"
                v-model="user.cpf"
                v-mask="['###.###.###-##']"
                required/>
            <error-label v-if="auth.validate.cpf" :message="auth.validate.cpf[0]" />
          </v-col>

          <v-col cols="12">
            <v-text-field
                id="password"
                name="password"
                type="password"
                variant="outlined"
                label="Senha"
                v-model="user.password"
                required/>
            <error-label v-if="auth.validate.password" :message="auth.validate.password[0]" />
          </v-col>

          <v-col cols="12">
            <v-text-field
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                variant="outlined"
                label="Confirmar Senha"
                v-model="user.password_confirmation"
                required/>
          </v-col>

          <v-btn
              class="mt-4"
              color="primary"
              block
              @click.prevent="handleRegister"
          >
            Salvar
          </v-btn>

          <v-col cols="12 d-flex justify-end">
            <v-btn variant="plain" @click.prevent="handleRedirect">
              login
            </v-btn>
          </v-col>
        </v-form>
      </v-card-text>
    </v-card>
  </v-container>
</template>