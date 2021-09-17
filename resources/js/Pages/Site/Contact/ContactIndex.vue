<template>
  <site-layout>
    <div class="bg-white shadow-sm">
      <contact-table
        :items="contacts.items"
      />
    </div>
  </site-layout>
</template>

<script>
import SiteLayout from '@/Layouts/SiteLayout';
import ContactTable from '@/components/Site/ContactTable';

export default {
  name: 'ContactIndex',
  components: { ContactTable, SiteLayout },
  props: {
    contacts: Array,
  },
};
</script>

<style scoped>

</style>
