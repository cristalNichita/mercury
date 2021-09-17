<template>
  <div
    v-loading="loading"
    class="form-group"
  >
    <label class="text-muted">Метки:</label>
    <div class="input-group">
      <el-checkbox
        v-model="isNew"
        @change="setLabels"
      >
        Новинка
      </el-checkbox>
      <el-checkbox
        v-model="isSale"
        @change="setLabels"
      >
        Акция
      </el-checkbox>
      <el-checkbox
        v-model="isOffer"
        @change="setLabels"
      >
        Выгодное предложение
      </el-checkbox>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProductLabels',
  props: {
    product: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      loading: false,
      isNew: !!this.product.is_new,
      isSale: !!this.product.is_sale,
      isOffer: !!this.product.is_offer,
    };
  },
  methods: {
    setLabels() {
      this.loading = true;
      const params = { is_new: this.isNew, is_sale: this.isSale, is_offer: this.isOffer };
      axios.patch(route('catalog.product.update', this.product.id), params).catch(() => {
        this.$notify.error({
          title: 'Ошибка',
          message: 'При обновление товара произошла ошибка',
        });
      }).finally(() => {
        this.loading = false;
      });
    },
  },
};
</script>
