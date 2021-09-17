<template>
  <base-layout>
    <div class="row">
      <div class="col-2">
        <site-sidebar class="mb-3" />
      </div>
      <div class="col-10">
        <slot />
      </div>
    </div>
  </base-layout>
</template>

<script>
import BaseLayout from '@/Layouts/BaseLayout';
import SiteSidebar from '@/components/Sidebars/SiteSidebar';

export default {
  name: 'SiteLayout',
  components: { SiteSidebar, BaseLayout },
};
</script>

<style scoped>

</style>
