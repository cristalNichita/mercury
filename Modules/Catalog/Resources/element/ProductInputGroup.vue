<template>
  <div class="form-group">
    <label class="text-muted">{{ title }}</label>
    <div class="input-group">
      <input
        type="text"
        readonly
        class="form-control"
        :value="value"
      >
      <div
        v-if="append"
        class="input-group-append"
      >
        <span class="input-group-text">{{ append }}</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProductInputGroup',
  props: ['title', 'value', 'append'],
};
</script>

<style scoped>

</style>
