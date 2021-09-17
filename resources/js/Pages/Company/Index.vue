<template>
  <div>
    <div class="mb-3">
      <div class="d-flex justify-content-between flex-nowrap">
        <el-input
          placeholder="Иванов Иван Иванович"
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
      <el-table
        :data="companies"
        class="w-100"
        @row-click="rowClick"
      >
        <el-table-column
          prop="name"
          label="Название"
        />
        <el-table-column
          prop="inn"
          label="ИНН"
        />
        <el-table-column
          prop="ogrn"
          label="ОГРН"
        />
        <el-table-column
          prop="kpp"
          label="КПП"
        />
        <el-table-column
          prop="created_at"
          label="Регистрация"
        >
          <template #default="scope">
            <i class="el-icon-time" />
            <span style="margin-left: 10px">{{ $filters.timeFormat(scope.row.created_at) }}</span>
          </template>
        </el-table-column>
        <el-table-column
          prop="updated_at"
          label="Обновление"
        >
          <template #default="scope">
            <i class="el-icon-time" />
            <span style="margin-left: 10px">{{ $filters.timeFormat(scope.row.updated_at) }}</span>
          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
import UserLayout from '@/Layouts/UserLayout';

export default {
  name: 'Companies',
  // eslint-disable-next-line vue/no-unused-components
  components: { UserLayout },
  layout: (h, page) => h(UserLayout, [page]),
  props: {
    companies: Array,
  },
  methods: {
    handleEdit(index, row) {
      console.log(index, row);
    },
    handleDelete(index, row) {
      console.log(index, row);
    },
    rowClick(index) {
      console.log(index);
      this.$inertia.visit(this.route('users.company.edit', index.id));
    },
    createButtonListener() {
      this.$inertia.visit(this.route('users.company.create'));
    },
  },
};
</script>

<style scoped>

</style>
