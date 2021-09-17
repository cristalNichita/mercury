<template>
  <base-layout>
    <div class="row">
      <div class="col-2">
        <catalog-sidebar class="mb-3" />
      </div>
      <div class="col-10">
        <slot />
      </div>
    </div>
  </base-layout>
</template>

<script>
import BaseLayout from '@/Layouts/BaseLayout';
import CatalogSidebar from '@/components/Sidebars/CatalogSidebar';

export default {
  name: 'CatalogLayout',
  components: { CatalogSidebar, BaseLayout },
};
</script>
