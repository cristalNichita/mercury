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
    {{ complaint }}
  </div>
</template>

<script>
import ComplaintLayout from '@/Layouts/ComplaintLayout';

export default {
  name: 'View',
  layout: (h, page) => h(ComplaintLayout, [page]),
  props: {
    complaint: Object,
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
