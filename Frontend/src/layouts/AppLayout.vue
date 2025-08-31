<script setup lang="ts">
import {useAuthStore} from "@/services/useAuth.js";

const {user, logout} = useAuthStore();
</script>
<template>
  <v-app>
    <v-app-bar color="primary" dark>
      <v-toolbar-title>Gerencidor de Usuarios e Produtos</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-menu>
        <template v-slot:activator="{ props }">
          <v-btn
              v-bind="props"
          >
            {{user.name}} <font-awesome-icon :icon="['fas', 'chevron-down']" />
          </v-btn>
        </template>
        <v-list>
          <v-list-item>
            <v-list-item-title class="cursor-pointer" @click.prevent="logout">
              <font-awesome-icon :icon="['fas', 'power-off']" />
              Sair
            </v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>

    <v-main>
      <v-container>
        <RouterView />
      </v-container>
    </v-main>
  </v-app>
</template>