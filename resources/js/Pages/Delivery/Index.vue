<template>
  <div>
    <div class="mb-3">
      <div class="d-flex justify-content-between flex-nowrap">
        <el-input
          placeholder="Иванов Иван Иванович"
          prefix-icon="el-icon-search"
          class="mr-3"
        />
      </div>
    </div>

    <div class="bg-white shadow-sm">
      <el-table
        ref="table"
        :data="items"
        class="w-100"
        :default-sort="defaultSort"
        @row-click="rowClick"
        @sort-change="rowSortChange"
      >
        <el-table-column
          prop="id"
          label="ID"
          sortable="custom"
        >
          <template #default="scope">
            #{{ scope.row.id }}
          </template>
        </el-table-column>

        <el-table-column
          prop="name"
          label="Название"
        />

        <el-table-column
          prop="active"
          label="Активность"
          :filters="[{ text: 'Да', value: true }, { text: 'Нет', value: false }]"
          :filter-method="filterActive"
          filter-placement="bottom-end"
        >
          <template #default="scope">
            <el-tag
              :type="scope.row.active ? 'success': 'primary'"
              disable-transitions
            >
              {{ scope.row.active ? 'Да' : 'Нет' }}
            </el-tag>
          </template>
        </el-table-column>

        <el-table-column
          prop="code"
          label="Уникальный код"
        />

        <el-table-column
          prop="sort"
          label="Сортировка"
        />

        <el-table-column
          prop="created_at"
          label="Создан"
        >
          <template #default="scope">
            <i class="el-icon-time" />
            <span style="margin-left: 10px">{{ $filters.timeFormat(scope.row.created_at) }}</span>
          </template>
        </el-table-column>
        <el-table-column
          prop="updated_at"
          label="Обновлён"
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
import OrderLayout from '@/Layouts/OrderLayout';

export default {
  name: 'Index',
  layout: (h, page) => h(OrderLayout, [page]),
  props: {
    items: {
      type: Array,
      default: () => ([]),
    },
    sort: {
      type: String,
      default: () => ('id-asc'),
    },
  },
  methods: {
    rowClick(index) {
      this.$inertia.visit(this.route('delivery.view', index.id));
    },
    filterActive(value, row) {
      return row.active === value;
    },
  },
};
</script>

<style scoped>

</style>
