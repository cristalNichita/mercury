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
        :item="item"
      />

      <el-row
        v-loading="isSaving"
        type="flex"
        class="row-bg mt-4"
      >
        <el-button
          type="primary"
          @click="save"
        >
          Сохранить
        </el-button>
        <el-button
          type="danger"
          @click="askDeleteConfirm = true"
        >
          Удалить
        </el-button>
      </el-row>

      <el-dialog
        v-model="askDeleteConfirm"
        title="Удаление Слайдера"
        width="30%"
        center
      >
        <span>Вы уверены, что хотите удалить данный справочник?</span>
        <template #footer>
          <el-button @click="askDeleteConfirm = false">
            Отмена
          </el-button>
          <el-button
            type="danger"
            @click="deleteItem"
          >
            Да, удалить
          </el-button>
        </template>
      </el-dialog>
    </div>
  </settings-layout>
</template>

<script>
import SettingsLayout from '@/Layouts/SettingsLayout';
import GlobalDirectoryItemForm from '@/components/Settings/GlobalDirectoryItemForm';

export default {
  name: 'GlobalDirectoryItemEdit',
  components: { GlobalDirectoryItemForm, SettingsLayout },
  props: {
    directory: Object,
    item: Object,
  },
  data() {
    return ({
      askDeleteConfirm: false,
    });
  },

  mounted() {
    this.$refs.form.initForm();
  },

  methods: {
    backClick() {
      this.$inertia.visit(route('settings.directory.edit', this.directory.id));
    },

    deleteItem() {
      this.$inertia.delete(route('settings.directory.item.destroy', { directory: this.directory.id, item: this.item.id }), {
        onSuccess: () => {
          this.$notify.success({
            title: 'Успешно',
            message: 'Справочник был удален!',
          });
        },
      });
    },

    save() {
      this.$refs.form.submit();
    },
  },
};
</script>

<style scoped>

</style>
