<template>
  <el-tag :type="type">
    {{ title }}
  </el-tag>
</template>

<script>
export default {
  name: 'OrderPaymentStatus',
  props: {
    status: {
      type: String,
      required: true,
    },
  },
  computed: {
    title() {
      switch (this.status) {
      case 'waiting':
        return 'Ожидание';
      case 'paid':
        return 'Оплачено';
      default:
        return 'Неизвестно';
      }
    },
    type() {
      switch (this.status) {
      case 'waiting':
        return 'info';
      case 'paid':
        return 'success';
      default:
        return '';
      }
    },
  },
};
</script>

<style scoped>

</style>
