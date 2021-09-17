<template>
  <el-table
    :data="products"
    class="w-100"
  >
    <el-table-column
      prop="title"
      label="Наименование"
    >
      <template #default="scope">
        <div class="text-truncate">
          <inertia-link :href="route('catalog.product', scope.row.id)">
            {{ scope.row.title }}
          </inertia-link>
        </div>
        <div class="text-muted">
          <span v-if="scope.row.id_1c">
            1C: {{ scope.row.id_1c }}
          </span>
        </div>
      </template>
    </el-table-column>
    <el-table-column
      align="right"
      prop="price"
      label="Цена"
      width="110"
      sortable
    >
      <template #default="scope">
        <div class="text-right">
          <del
            v-if="scope.row.old_price"
            class="small"
          >{{ $filters.moneyFormat(scope.row.old_price) }}</del>
          <div>{{ $filters.moneyFormat(scope.row.price) }} р.</div>
        </div>
      </template>
    </el-table-column>

    <el-table-column
      align="right"
      prop="quantity"
      label="Остаток"
      width="110"
      sortable
    >
      <template #default="scope">
        <div class="text-right">
          <div>{{ $filters.moneyFormat(scope.row.quantity) }} шт.</div>
        </div>
      </template>
    </el-table-column>

    <el-table-column
      align="right"
      width="100"
    >
      <template #default="scope">
        <el-button
          type="danger"
          size="mini"
          class="w-100"
          @click="() => removeProduct(scope.row)"
        >
          Убрать
        </el-button>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'SpecialProductTable',
  props: {
    products: Array,
    special_field: String,
  },

  methods: {
    removeProduct(product) {
      const route = this.route(
        'catalog.special-products.update',
        {
          special_field: this.special_field,
          product: product.id,
        },
      );
      this.$inertia.patch(route);
    },
  },
};
</script>

<style scoped>

</style>
