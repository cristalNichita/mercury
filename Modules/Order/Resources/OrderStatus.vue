<template>
  <el-tag :type="type">
    {{ title }}
  </el-tag>
</template>

<script>
export default {
  name: 'OrderStatus',
  props: {
    status: {
      type: String,
      required: true,
    },
  },
  computed: {
    title() {
      switch (this.status) {
      case 'new':
        return 'Новый';
      case 'process':
        return 'Согласование';
      case 'shipped':
        return 'Отправлен';
      case 'finish':
        return 'Завершен';
      case 'cancel':
        return 'Отменен';
      default:
        return 'Неизвестно';
      }
    },
    type() {
      switch (this.status) {
      case 'new':
        return 'success';
      case 'process':
        return 'warning';
      case 'shipped':
        return 'success';
      case 'finish':
        return '';
      case 'cancel':
        return 'danger';
      default:
        return '';
      }
    },
  },
};
</script>

<style scoped>

</style>
