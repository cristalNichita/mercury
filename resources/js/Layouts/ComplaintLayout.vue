<template>
  <base-layout>
    <div class="row">
      <div class="col-2">
        <complaint-sidebar class="mb-3" />
      </div>
      <div class="col-10">
        <slot />
      </div>
    </div>
  </base-layout>
</template>

<script>
import BaseLayout from '@/Layouts/BaseLayout';
import ComplaintSidebar from '@/components/Sidebars/ComplaintSidebar';

export default {
  name: 'ComplaintLayout',
  components: { ComplaintSidebar, BaseLayout },
};
</script>
