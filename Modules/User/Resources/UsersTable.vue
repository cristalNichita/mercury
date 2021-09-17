<template>
  <el-table
    :data="users"
    class="w-100"
    :default-sort="defaultSort"
    @sort-change="sortChange"
  >
    <el-table-column
      prop="id"
      label="ID"
      width="64"
      sortable
    />

    <el-table-column
      prop="title"
      label="ФИО"
    >
      <template #default="scope">
        <div class="text-truncate">
          <inertia-link :href="route('users.edit', scope.row.id)">
            {{ scope.row.name }}
          </inertia-link>
        </div>
      </template>
    </el-table-column>

    <el-table-column
      prop="email"
      label="E-Mail"
      sortable
    />

    <el-table-column
      prop="phone"
      label="Телефон"
      sortable
    />

    <el-table-column
      prop="contact"
      label="Контактное лицо"
    >
      <template #default="scope">
        <user-contact
          v-if="scope.row.contact"
          :contact="scope.row.contact"
        />
      </template>
    </el-table-column>

    <el-table-column
      prop="created_at"
      label="Регистрация"
      sortable
    >
      <template #default="scope">
          {{ $filters.timeFormat(scope.row.created_at) }}
      </template>
    </el-table-column>

    <el-table-column
      prop="updated_at"
      label="Обновление"
      sortable
    >
      <template #default="scope">
        {{ $filters.timeFormat(scope.row.updated_at) }}
      </template>
    </el-table-column>
    <slot />
  </el-table>
</template>
<script>

import UserContact from '@modules/User/Resources/UserContact';

export default {
  name: 'UsersTable',
  components: {
    UserContact,
  },
  props: {
    users: {
      type: Array,
      required: true,
    },
  },
  computed: {
    // eslint-disable-next-line consistent-return,vue/return-in-computed-property
    defaultSort() {
      if (this.$page.props.sort) {
        const [prop, order] = this.$page.props.sort.split('-');
        return { prop, order };
      }
    },
  },
  methods: {
    sortChange(column) {
      let sort;
      if (column.prop) {
        sort = `${column.prop}-${column.order}`;
      }

      this.$inertia.replace(route(route().current(), route().params), {
        method: 'get',
        data: { sort },
        replace: false,
        preserveState: true,
        preserveScroll: false,
        onBefore: () => {
          this.$emit('update:loading', true);
        },
        onFinish: () => {
          this.$emit('update:loading', false);
        },
      });
    },
  },
};
</script>

<style scoped>

</style>
