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
      <global-directory-form
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
import GlobalDirectoryForm from '@/components/Settings/GlobalDirectoryForm';

export default {
  name: 'GlobalDirectoryCreate',
  components: { GlobalDirectoryForm, SettingsLayout },
  props: {
    directory: Object,
  },

  methods: {
    backClick() {
      this.$inertia.visit(route('settings.directory'));
    },

    create() {
      this.$refs.form.submit();
    },
  },
};
</script>

<style scoped>

</style>
