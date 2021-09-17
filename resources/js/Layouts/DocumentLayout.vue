<template>
  <base-layout>
    <div class="row">
      <div class="col-2">
        <document-sidebar class="mb-3" />
      </div>
      <div class="col-10">
        <slot />
      </div>
    </div>
  </base-layout>
</template>

<script>
import BaseLayout from '@/Layouts/BaseLayout';
import DocumentSidebar from '@/components/Sidebars/DocumentSidebar';

export default {
  name: 'DocumentLayout',
  components: { DocumentSidebar, BaseLayout },
};
</script>
