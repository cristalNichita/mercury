<template>
  <el-table
    :data="events"
    class="w-100"
    @row-click="openEvent"
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
  </el-table>
</template>

<script>
export default {
  name: 'MailingEventTable',
  props: {
    events: Array,
  },

  methods: {
    openEvent(row) {
      this.$inertia.visit(route('events.edit', row.id));
    },
  },
};
</script>

<style scoped>

</style>
