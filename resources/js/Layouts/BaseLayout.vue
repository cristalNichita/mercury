<template>
  <div>
    <Header class="mb-4" />
    <div class="container-fluid container-xxl">
      <slot />
    </div>
  </div>
</template>

<script>
import Header from '@/components/Header';

export default {
  name: 'BaseLayout',
  components: { Header },
};
</script>
