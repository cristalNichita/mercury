<template>
  <el-table
    :data="directories"
    class="w-100"
    @row-click="openDirectory"
  >
    <el-table-column
      label="Название"
    >
      <template #default="scope">
        {{ scope.row.name }}
      </template>
    </el-table-column>
    <el-table-column
      label="Код"
    >
      <template #default="scope">
        {{ scope.row.code }}
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'GlobalDirectoryTable',
  props: {
    directories: Array,
  },

  methods: {
    openDirectory(row) {
      this.$inertia.visit(route('settings.directory.edit', row.id));
    },
  },
};
</script>

<style scoped>

</style>
