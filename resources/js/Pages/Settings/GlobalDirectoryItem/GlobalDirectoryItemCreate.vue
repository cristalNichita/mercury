<template>
  <settings-layout>
    <div class="mb-3">
      <div class="d-flex  flex-nowrap align-items-center">
        <el-button
          type="primary"
          icon="el-icon-arrow-left"
          class="mr-4"
          @click="backClick"
        >
          Назад
        </el-button>
      </div>
    </div>

    <div class="bg-white shadow-sm p-3">
      <global-directory-item-form
        ref="form"
        :directory="directory"
      />

      <el-row
        v-loading=""
        type="flex"
        class="row-bg mt-4"
      >
        <el-button
          type="primary"
          @click="create"
        >
          Создать
        </el-button>
      </el-row>
    </div>
  </settings-layout>
</template>

<script>
import SettingsLayout from '@/Layouts/SettingsLayout';
import GlobalDirectoryItemForm from '@/components/Settings/GlobalDirectoryItemForm';

export default {
  name: 'GlobalDirectoryItemCreate',
  components: { GlobalDirectoryItemForm, SettingsLayout },
  props: {
    directory: Object,
    item: Object,
  },

  methods: {
    backClick() {
      this.$inertia.visit(route('settings.directory.edit', this.directory.id));
    },

    create() {
      this.$refs.form.submit();
    },
  },
};
</script>

<style scoped>

</style>
