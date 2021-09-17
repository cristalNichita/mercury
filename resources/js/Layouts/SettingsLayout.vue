<template>
  <base-layout>
    <div class="row">
      <div class="col-2">
        <settings-sidebar class="mb-3" />
      </div>
      <div class="col-10">
        <slot />
      </div>
    </div>
  </base-layout>
</template>

<script>
import BaseLayout from '@/Layouts/BaseLayout';
import SettingsSidebar from '@/components/Sidebars/SettingsSidebar';

export default {
  name: 'SettingsLayout',
  components: { SettingsSidebar, BaseLayout },
};
</script>

<style scoped>

</style>
