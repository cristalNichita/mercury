<template>
  <el-table
    :data="items"
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
          @click="createItem"
        >
          Добавить
        </el-button>
      </template>
      <template #default="scope">
        <el-button
          size="mini"
          @click="() => editItem(scope.row)"
        >
          Изменить
        </el-button>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'GlobalDirectoryItemTable',
  props: {
    directoryId: Number,
    items: Array,
  },

  methods: {
    createItem() {
      this.$inertia.visit(route('settings.directory.item.create', { directory: this.directoryId }));
    },

    editItem(row) {
      this.$inertia.visit(route('settings.directory.item.edit', { directory: this.directoryId, item: row.id }));
    },
  },
};
</script>

<style scoped>

</style>
