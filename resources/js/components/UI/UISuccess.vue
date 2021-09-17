<template>
  <el-alert
    v-if="title"
    :title="title"
    type="success"
    show-icon
    class="mb-3"
    :closable="false"
  />
</template>

<script>
export default {
  name: 'UiSuccess',
  props: {
    title: {
      type: String,
      required: true,
    },
  },
};
</script>

<style scoped>

</style>
