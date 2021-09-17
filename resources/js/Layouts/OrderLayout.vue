<template>
  <base-layout>
    <div class="row">
      <div class="col-2">
        <order-sidebar class="mb-3" />
      </div>
      <div class="col-10">
        <slot />
      </div>
    </div>
  </base-layout>
</template>

<script>
import BaseLayout from '@/Layouts/BaseLayout';
import OrderSidebar from '@/components/Sidebars/OrderSidebar';

export default {
  name: 'OrderLayout',
  components: { OrderSidebar, BaseLayout },
};
</script>
