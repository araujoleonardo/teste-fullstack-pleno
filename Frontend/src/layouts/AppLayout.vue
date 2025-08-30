<script setup lang="ts">
import { ChevronDown, PowerOff } from "lucide-vue-next";
import {useAuthStore} from "@/services/useAuth.js";

const {user, logout} = useAuthStore();
</script>
<template>
  <v-app>
    <v-app-bar color="primary" dark>
      <v-toolbar-title>Usuários</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-menu>
        <template v-slot:activator="{ props }">
          <v-btn
              v-bind="props"
          >
            {{user.name}} <ChevronDown />
          </v-btn>
        </template>
        <v-list>
          <v-list-item>
            <v-list-item-title @click.prevent="logout">
              <PowerOff />
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