<template>
  <div class="b-layout">
    <div class="bg-white b-layout__wrapper p-5 border shadow-lg rounded">
      <div class="text-center pb-5">
        <img
          src="@assets/logo.png"
          alt=""
        >
      </div>
      <slot />
    </div>
  </div>
</template>

<script>
export default {
  name: 'GuestLayout',
};
</script>

<style scoped lang="scss">
  // Не получилось подгружать глобально (
  @import "@css/variables";
  @import "~bootstrap/scss/functions";
  @import "~bootstrap/scss/variables";
  @import "~bootstrap/scss/mixins";

  .b-layout {
    display: flex;
    justify-content: center;
    align-items: center;
    background: $gray-200;
    min-height: 100vh;

    &__wrapper {
      width: 500px;
    }
  }
</style>
