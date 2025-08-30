<script setup lang="ts">
import {useAuthStore} from "@/services/useAuth.ts";
import {useRouter} from "vue-router";
import {reactive, ref} from "vue";
import type {UserAuth} from "@/types/user-auth";

const router = useRouter();
const auth = useAuthStore();
const isLoading = ref(false);
const errorMessage = ref<string | undefined>(undefined);
const user = reactive<UserAuth>({
  email: undefined,
  password: undefined,
  remember_me: false
});

const handleLogin = async () => {
  try {
    isLoading.value = true;
    errorMessage.value = undefined;

    // Chamada correta para o login
    await auth.login(user.email!, user.password!);

    // Redireciona após login bem-sucedido
    router.push('/');
  } catch (error) {
    errorMessage.value = 'Falha no login. Verifique suas credenciais.';
  } finally {
    isLoading.value = false;
  }
};
</script>

<template>
  <v-container class="d-flex justify-center align-center" style="height: 100vh;">
    <v-card class="pa-4" style="width: 25rem;">
      <v-card-title class="justify-center">Login</v-card-title>
      <v-card-text>
        <v-form ref="loginForm">
          <v-col cols="12">
            <v-text-field
                id="email"
                name="email"
                type="email"
                variant="outlined"
                label="Email"
                v-model="user.email"
                required/>
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
          </v-col>
          <v-btn
              class="mt-4"
              color="primary"
              block
              @click.prevent="handleLogin"
          >
            Entrar
          </v-btn>
        </v-form>
      </v-card-text>
    </v-card>
  </v-container>
</template>