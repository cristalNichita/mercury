<template>
  <settings-layout>
    <div class="mb-3">
      <div class="d-flex justify-content-end flex-nowrap">

        <el-button
          type="primary"
          icon="el-icon-plus"
          @click="createDirectory"
        >
          Добавить
        </el-button>
      </div>
    </div>

    <div class="bg-white shadow-sm">
      <global-directory-table
        :directories="directories.data"
      />
      <ui-pagination
        :max="directories.last_page"
        :page="directories.current_page"
      />
    </div>
  </settings-layout>
</template>

<script>
import SettingsLayout from '@/Layouts/SettingsLayout';
import GlobalDirectoryTable from '@/components/Settings/GlobalDirectoryTable';

export default {
  name: 'GlobalDirectoryIndex',
  components: { GlobalDirectoryTable, SettingsLayout },
  props: {
    directories: Array,
  },

  methods: {
    createDirectory() {
      this.$inertia.visit(route('settings.directory.create'));
    },
  },
};
</script>

<style scoped>

</style>
