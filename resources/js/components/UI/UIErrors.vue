<template>
  <el-alert
    v-if="errors && Object.keys(errors).length"
    :title="title"
    type="error"
    show-icon
    class="mb-3"
    :closable="false"
  >
    <div
      v-for="(error, key) in errors"
      :key="key"
    >
      {{ error }}
    </div>
  </el-alert>
</template>

<script>
export default {
  name: 'UiErrors',
  props: {
    title: {
      type: String,
      default: 'Ошибки',
    },
  },
  computed: {
    errors() {
      return this.$page.props.errors;
    },
  },
};
</script>

<style scoped>

</style>
