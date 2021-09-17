<template>
  <el-table
    :data="statuses"
    class="w-100"
  >
    <el-table-column
      label="Название"
    >
      <template #default="scope">
        {{ scope.row.name }}
      </template>
    </el-table-column>

    <el-table-column
      align="right"
    >
      <template
        #header
      >
        <el-button
          type="primary"
          icon="el-icon-plus"
          size="mini"
          @click="createStatus"
        >
          Добавить
        </el-button>
      </template>
      <template #default="scope">
        <el-button
          size="mini"
          @click="() => editStatus(scope.row)"
        >
          Изменить
        </el-button>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'MailingEventStatusTable',
  props: {
    eventId: Number,
    statuses: Array,
  },

  methods: {
    createStatus() {
      this.$inertia.visit(route('events.statuses.create', { event: this.eventId }));
    },

    editStatus(row) {
      this.$inertia.visit(route('events.statuses.edit', { event: this.eventId, status: row.id }));
    },
  },
};
</script>

<style scoped>

</style>
