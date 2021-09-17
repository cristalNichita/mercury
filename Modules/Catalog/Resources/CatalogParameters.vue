<template>
  <catalog-layout>
    <div
      v-for="param in parameters"
      :key="param.id"
      class="mb-3 bg-white border rounded p-3"
    >
      <div class="h5">
        {{ param.title }}
      </div>
      <ul class="row">
        <li
          v-for="value in param.values"
          :key="value.id"
          class="col-12 col-md-3"
        >
          {{ value.value }}
        </li>
      </ul>
    </div>
  </catalog-layout>
</template>

<script>
import CatalogLayout from '@/Layouts/CatalogLayout';

export default {
  name: 'Parameters',
  components: { CatalogLayout },
  props: {
    parameters: {
      type: Array,
      required: true,
    },
  },
};
</script>

<style scoped>

</style>
