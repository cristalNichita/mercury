<template>
  <base-layout>
    <div class="row">
      <div class="col-2">
        <user-sidebar class="mb-3" />
      </div>
      <div class="col-10">
        <slot />
      </div>
    </div>
  </base-layout>
</template>

<script>
import BaseLayout from '@/Layouts/BaseLayout';
import UserSidebar from '@/components/Sidebars/UserSidebar';

export default {
  name: 'UserLayout',
  components: { UserSidebar, BaseLayout },
};
</script>
