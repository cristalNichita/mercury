<template>
  <el-table
    class="w-100"
    row-key="row_id"
    :data="companies"
  >

    <el-table-column
      prop="name"
      label="Наименование"
    >
      <template #default="scope">
        <div class="text-truncate">
          <inertia-link :href="route('users.company.show', scope.row.id)">
            {{ scope.row.name || 'Без названия' }}
          </inertia-link>
        </div>
      </template>
    </el-table-column>

    <el-table-column
      prop="type_1c"
      label="Тип"
      width="300"
    />

    <el-table-column
      prop="inn"
      width="140"
      label="ИНН"
    />
  </el-table>
</template>

<script>
export default {
  name: 'HoldingCompaniesTable',
  props: {
    companies: {
      type: Array,
      required: true,
    },
  },
};
</script>
