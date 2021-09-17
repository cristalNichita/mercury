<template>
  <div class="text-truncate">
    <inertia-link :href="route('users.contacts.show', contact.id)">
      {{ contact.name }}
    </inertia-link>
  </div>
</template>

<script>
export default {
  name: 'UserContact',
  props: {
    contact: {
      type: Object,
      required: true,
    },
  },
};
</script>
