<template>
  <div>
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
      </div>
    </div>
    {{ order }}
  </div>
</template>

<script>
import OrderLayout from '@/Layouts/OrderLayout';

export default {
  name: 'View',
  layout: (h, page) => h(OrderLayout, [page]),
  props: {
    order: Object,
  },
  methods: {
    backClick() {
      // eslint-disable-next-line no-restricted-globals
      history.go(-1);
    },
  },
};
</script>

<style scoped>

</style>
