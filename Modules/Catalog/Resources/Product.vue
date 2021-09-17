<template>
  <catalog-layout>
    <div class="mb-3">
      <div class="d-flex  flex-nowrap align-items-center">
        <el-button
          type="primary"
          icon="el-icon-arrow-left"
          class="mr-4"
          @click="backClick"
        >
          Назад
        </el-button>

        <div class="text-truncate h4 line-height-1 m-0">
          {{ product.title }}
        </div>
      </div>
    </div>
    <div class="bg-white shadow-sm p-3">
      <div class="row align-items-start mb-3">
        <div class="col">
          <div class="h2">
            {{ product.title }}
          </div>
        </div>
        <div class="col-auto">
          ID: {{ product.id }}<br>1C: {{ product.id_1c }}
        </div>
      </div>

      <div class="mb-3">
        <span class="text-muted">Адрес: </span>
        <a
          :href="`https://mercury-front.dev.echo-company.ru/product/${product.slug}`"
          target="_blank"
        >https://mercury-front.dev.echo-company.ru/product/{{ product.slug }}</a>
      </div>
      <div class="mb-3">
        <span class="text-muted">Категория: </span>
        <span
          v-for="category in product.categories"
          :key="category.id"
        >
          <inertia-link :href="route('catalog.category', category.id)">
            {{ category.title }}
          </inertia-link>
        </span>
        <span v-if="!product.categories">
          Отсутсвует
        </span>
      </div>

      <div class="row mb-4">
        <div class="col-2">
          <product-input-group
            title="Цена:"
            append="руб"
            :value="product.price"
          />
        </div>
        <div class="col-2">
          <product-input-group
            title="Склад в Москве:"
            append="шт"
            :value="product.quantity_main"
          />
        </div>
        <div class="col-2">
          <product-input-group
            title="Удаленный склад:"
            append="шт"
            :value="product.quantity_remote"
          />
        </div>
        <div class="col-2">
          <product-input-group
            title="Вес:"
            append="кг"
            :value="product.weight"
          />
        </div>
        <div class="col-2">
          <product-input-group
            title="Объем:"
            append="л"
            :value="product.volume"
          />
        </div>
      </div>
      <div class="mb-4">
        <product-labels :product="product" />
      </div>

      <div class="mb-3">
        <div class="text-muted">
          Описание:
        </div>
        <div v-html="product.description" />
      </div>
      <div
        v-if="product.gallery.length"
        class="mb-3"
      >
        <div class="text-muted">
          Изображения:
        </div>
        <div class="d-flex">
          <div
            v-for="(image, index) in product.gallery"
            :key="index"
            class="mr-3 mb-3 p-1 border rounded"
          >
            <img
              :src="image.small"
              :alt="product.title"
            >
          </div>
        </div>
      </div>

      <div
        v-if="product.params.length"
        class="mb-3"
      >
        <div class="text-muted">
          Параметры:
        </div>
        <table class="table table-striped table-sm">
          <tr
            v-for="(param, index) in product.params"
            :key="`p_${index}`"
            class="border-bottom"
          >
            <td class="text-right text-nowrap">
              {{ param.title }}:
            </td>
            <td class="w-100">
              {{ param.value }}
            </td>
          </tr>
        </table>
      </div>
      <div>
        <div class="text-muted">
          Рекомендуемые товары:
        </div>
        <div class="mb-3">
          <search-product
            :parent_product="product.id"
          />
        </div>

        <recommended-product-table
          v-loading="loading"
          :products="recommended"
          @update:loading="loading = $event"
        />
      </div>
    </div>
  </catalog-layout>
</template>

<script>
import CatalogLayout from '@/Layouts/CatalogLayout';
import ProductInputGroup from '@modules/Catalog/Resources/element/ProductInputGroup';
import ProductLabels from '@modules/Catalog/Resources/element/ProductLabels';
import RecommendedProductTable from '@/components/Catalog/RecommendedProductTable';
import SearchProduct from '@/components/Catalog/SearchProduct';

export default {
  name: 'Product',
  components: {
    ProductLabels,
    ProductInputGroup,
    CatalogLayout,
    SearchProduct,
    RecommendedProductTable,
  },
  props: {
    filter: Object,
    product: Object,
    recommended: Array,
  },
  data() {
    return {
      loading: false,
      filter_show: false,
    };
  },
  computed: {},
  mounted() {
  },
  methods: {
    backClick() {
      this.$inertia.visit(route('catalog.products'));
    },
  },
};
</script>

<style scoped>

</style>
