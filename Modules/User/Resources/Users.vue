<template>
  <div>
    <div class="mb-3">
      <div class="d-flex justify-content-between flex-nowrap">
        <el-input
          placeholder="Быстрый поиск"
          prefix-icon="el-icon-search"
          class="mr-3"
        />
        <el-button
          type="primary"
          icon="el-icon-plus"
          @click="createButtonListener"
        >
          Добавить
        </el-button>
      </div>
    </div>

    <div class="bg-white shadow-sm">
      <users-table
        v-loading="loading"
        :users="users.data"
        @update:loading="loading = $event"
      />
      <ui-pagination
        :max="users.meta.last_page"
        :page="users.meta.current_page"
        @update:loading="loading = $event"
      />
    </div>
  </div>
</template>

<script>
import UserLayout from '@/Layouts/UserLayout';
import UiPagination from '@/components/UI/UiPagination';
import UsersTable from '@modules/User/Resources/UsersTable';

export default {
  name: 'Users',
  components: { UsersTable, UiPagination },
  layout: (h, page) => h(UserLayout, [page]),
  props: {
    users: {
      type: Object,
      required: true,
    },
    sort: {
      type: String,
      default: 'id-desc',
    },
  },
  data() {
    return {
      loading: false,
    };
  },
  methods: {
    createButtonListener() {
      this.$inertia.visit(this.route('users.create', { role: 4 }));
    },
  },
};
</script>

<style scoped>

</style>
