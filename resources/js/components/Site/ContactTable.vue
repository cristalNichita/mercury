<template>
  <el-table
    :data="items"
    class="w-100"
    @row-click="editItem"
  >
    <el-table-column
      label="id"
      align="right"
      width="50"
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
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'ContactTable',
  props: {
    items: Array,
  },

  methods: {
    createItem() {
      this.$inertia.visit(route('site.contacts.create'));
    },

    editItem(row) {
      this.$inertia.visit(route('site.contacts.edit', row.id));
    },
  },
};
</script>

<style scoped>

</style>
