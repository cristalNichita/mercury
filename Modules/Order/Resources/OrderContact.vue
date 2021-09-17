<template>
  <div class="b-order-contact">
    <inertia-link :href="route('users.contacts.show', order.contact_id)">
      {{ fio }}
    </inertia-link>
    <br>
    <a
      v-if="phone"
      :href="`tel:+${$filters.clearPhone(phone)}`"
    >
      {{ phone }}
    </a>
    <br>
    <a
      v-if="email"
      :href="`mailto:${email}`"
    >
      {{ email }}
    </a>
  </div>
</template>

<script>
export default {
  name: 'OrderContact',
  props: {
    order: {
      type: Object,
      required: true,
    },
  },
  computed: {
    fio() {
      return this.order.params?.contactFio ?? this.order.contact?.name ?? 'ФИО отсутсвует';
    },
    email() {
      return this.order.params?.contactEmail ?? this.order.contact?.email ?? '';
    },
    phone() {
      return this.order.params?.contactPhone ?? this.order.contact?.phone ?? '';
    },
  },
};
</script>
