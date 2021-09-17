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
        <div
          v-if="scope.row.product"
          class="text-truncate"
        >
          <inertia-link :href="route('catalog.product', scope.row.product.id)">
            {{ scope.row.product.title }}
          </inertia-link>
        </div>
        <div
          v-else
          class="text-truncate"
        >
          <inertia-link :href="route('catalog.product', scope.row.recommended_product.id)">
            {{ scope.row.recommended_product.title }}
          </inertia-link>
        </div>
        <div
          v-if="scope.row.product"
          class="text-muted"
        >
          <span v-if="scope.row.product.id_1c">
            1C: {{ scope.row.product.id_1c }}
          </span>
        </div>
        <div
          v-else
          class="text-muted"
        >
          <span v-if="scope.row.recommended_product.id_1c">
            1C: {{ scope.row.recommended_product.id_1c }}
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
        <div
          v-if="scope.row.product"
          class="text-right"
        >
          <del
            v-if="scope.row.product.old_price"
            class="small"
          >{{ $filters.moneyFormat(scope.row.product.old_price) }}</del>
          <div>{{ $filters.moneyFormat(scope.row.product.price) }} р.</div>
        </div>
        <div
          v-else
          class="text-right"
        >
          <del
            v-if="scope.row.recommended_product.old_price"
            class="small"
          >{{ $filters.moneyFormat(scope.row.recommended_product.old_price) }}</del>
          <div>{{ $filters.moneyFormat(scope.row.recommended_product.price) }} р.</div>
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
        <div
          v-if="scope.row.product"
          class="text-right"
        >
          <div>{{ $filters.moneyFormat(scope.row.product.quantity) }} шт.</div>
        </div>
        <div
          v-else
          class="text-right"
        >
          <div>{{ $filters.moneyFormat(scope.row.recommended_product.quantity) }} шт.</div>
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
  name: 'RecommendedProductTable',
  props: {
    products: Array,
  },
  methods: {
    removeProduct(recommeded) {
      const route = this.route('catalog.recommended-products.destroy', recommeded.id);
      this.$inertia.delete(route);
    },
  },
};
</script>

<style scoped>

</style>
