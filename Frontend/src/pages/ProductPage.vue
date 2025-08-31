<script setup lang="ts">
import {useRoute} from "vue-router";
import {ref} from "vue";
import ProductTable from "@/components/product/ProductTable.vue";

const route = useRoute();
const userId = ref<any>(route.params.id);
</script>

<template>
  <div>
    <ProductTable :id="userId"/>
  </div>
</template>