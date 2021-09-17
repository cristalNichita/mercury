<template>
  <el-table
    :data="mailings"
    class="w-100"
    @row-click="openMailing"
  >
    <el-table-column
      label="ID"
      width="100px"
    >
      <template #default="scope">
        {{ scope.row.id }}
      </template>
    </el-table-column>
    <el-table-column
      label="Название"
    >
      <template #default="scope">
        {{ scope.row.name }}
      </template>
    </el-table-column>
    <el-table-column
      label="Событие"
    >
      <template #default="scope">
        {{ (scope.row.event) ? scope.row.event.name : '-' }}
      </template>
    </el-table-column>
    <el-table-column
      label="Статус"
    >
      <template #default="scope">
        {{ (scope.row.status) ? scope.row.status.name : '-' }}
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'MailingTable',
  props: {
    mailings: Array,
  },

  methods: {
    openMailing(row) {
      this.$inertia.visit(route('mailing.edit', row.id));
    },
  },
};
</script>

<style scoped>

</style>
