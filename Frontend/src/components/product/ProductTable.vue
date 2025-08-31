<script setup lang="ts">
import AppPagination from "@/components/ui/AppPagination.vue";
import useProductTable from "@/composables/product/useProductTable.ts";
import ProductFormDialog from "@/components/product/ProductFormDialog.vue";

const props = withDefaults(defineProps<{
  id?: number;
}>(), {
  id: undefined,
});

const {
  loading,
  params,
  dataSet,
  getData,
  loadData,
  handleSort,
  handlePerPage,
  handleDelete,
  handleOpen,
  handleEdit,
  openDialog,
  product,
  user
} = useProductTable('/product', props.id);

</script>

<template>
  <div>
    <v-card>
      <v-card-title>
        Produtos do usuario:
      </v-card-title>
      <v-divider></v-divider>
      <v-card-item>
        <v-table>
          <thead>
            <tr>
              <th class="text-left">
                Nome
              </th>
              <th class="text-left">
                Email
              </th>
              <th class="text-left">
                CPF
              </th>
            </tr>
          </thead>
          <tbody>
          <tr>
            <td class="text-left">
              {{user.name}}
            </td>
            <td class="text-left">
              {{user.email}}
            </td>
            <td class="text-left">
              {{user.cpf}}
            </td>
          </tr>
          </tbody>
        </v-table>
      </v-card-item>
    </v-card>
    <br>
    <v-card>
      <v-card-title>
        <v-row align="center" justify="space-between">
          <v-col cols="auto">
            <v-text-field
                v-model="params.search"
                id="search"
                type="text"
                width="300"
                density="compact"
                placeholder="Nome | Preco"
                variant="outlined"
            />
          </v-col>
          <v-col cols="auto">
            <v-btn variant="outlined" :loading="loading" @click="handleOpen">
              <font-awesome-icon :icon="['fas', 'plus']" />
              Novo
            </v-btn>
          </v-col>
        </v-row>
      </v-card-title>
      <v-divider></v-divider>
      <v-card-item>
        <v-table>
          <thead>
          <tr>
            <th class="text-left">
              Nome
              <v-btn density="compact" variant="plain" @click="handleSort('name')" size="small">
                <font-awesome-icon :icon="['fas', 'sort']" />
              </v-btn>
            </th>
            <th class="text-left">
              Preco
              <v-btn density="compact" variant="plain" @click="handleSort('price')" size="small">
                <font-awesome-icon :icon="['fas', 'sort']" />
              </v-btn>
            </th>
            <th class="text-left">
              Descricao
              <v-btn density="compact" variant="plain" @click="handleSort('description')" size="small">
                <font-awesome-icon :icon="['fas', 'sort']" />
              </v-btn>
            </th>
            <th class="text-left">
              Criado em
              <v-btn density="compact" variant="plain" @click="handleSort('created_at')" size="small">
                <font-awesome-icon :icon="['fas', 'sort']" />
              </v-btn>
            </th>
            <th class="text-center">
              Opções
            </th>
          </tr>
          </thead>
          <tbody v-if="dataSet.data?.length">
          <tr v-for="(item) in dataSet.data" :key="item.id">
            <td>{{ item.name }}</td>
            <td>{{ item.price }}</td>
            <td>{{ item.description }}</td>
            <td>{{ item.createdAt }}</td>
            <td class="justify-space-between">
              <v-row align="center" justify="center">
                <v-col cols="auto">
                  <v-btn density="compact" color="blue-darken-2" variant="text" icon @click="handleEdit(item)">
                    <font-awesome-icon size="lg" :icon="['fas', 'pen-to-square']" />
                  </v-btn>
                </v-col>
                <v-col cols="auto">
                  <v-btn density="compact" color="red-darken-2" variant="text" icon @click="handleDelete(item)">
                    <font-awesome-icon size="lg" :icon="['fas', 'trash']" />
                  </v-btn>
                </v-col>
              </v-row>
            </td>
          </tr>
          </tbody>
          <tbody v-else>
          <tr>
            <td colspan="5" class="text-center">
              SEM DADOS DISPONÍVEIS
            </td>
          </tr>
          </tbody>
        </v-table>
      </v-card-item>
      <v-divider v-if="dataSet.total > 0"></v-divider>
      <v-card-actions v-if="dataSet.total > 0" class="pt-0">
        <AppPagination :data="dataSet" @page="getData" @per-page="handlePerPage"/>
      </v-card-actions>
    </v-card>
  </div>

  <ProductFormDialog
      :visible="openDialog.isOpen"
      :handleClose="
        () => {
          openDialog.isOpen = false
        }
      "
      :tipoForm="openDialog.tipoForm"
      :product="product"
      :userId="user.id"
      @reload="loadData"
  />
</template>

<style scoped>

</style>