<template>
  <catalog-layout>
    <div class="shadow-sm bg-white">
      <el-table
        :data="filteredBrands"
        stripe
      >
        <el-table-column
          label="ID"
          prop="id"
          width="70"
          sortable
        />
        <el-table-column
          label="Название"
          prop="title"
          sortable
        />
        <el-table-column
          align="right"
          width="400"
        >
          <template #header>
            <el-input
              v-model="search"
              size="mini"
              placeholder="Поиск по названию"
            />
          </template>
        </el-table-column>
      </el-table>
    </div>
  </catalog-layout>
</template>

<script>
import CatalogLayout from '@/Layouts/CatalogLayout';

export default {
  name: 'Brands',
  components: { CatalogLayout },
  props: {
    // eslint-disable-next-line no-bitwise,vue/require-prop-type-constructor
    filter: Object | Array,
    // eslint-disable-next-line no-bitwise,vue/require-prop-type-constructor
    brands: Object | Array,
  },
  data() {
    return {
      search: '',
    };
  },
  computed: {
    filteredBrands() {
      return this.brands.data
        .filter(
          (item) => !this.search || item.title.toLowerCase().includes(this.search.toLowerCase()),
        );
    },
  },
};
</script>
