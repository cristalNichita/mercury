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
      <mailing-event-status-form
        ref="form"
        :event="event"
        :status="status"
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
        title="Удаление статуса"
        width="30%"
        center
      >
        <span>Вы уверены, что хотите удалить данный статус?</span>
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
import MailingEventStatusForm from '@modules/Mailing/Resources/MailingEventStatusForm';

export default {
  name: 'GlobalDirectoryItemEdit',
  components: { MailingEventStatusForm, SettingsLayout },
  props: {
    event: Object,
    status: Object,
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
      this.$inertia.visit(route('events.edit', this.event.id));
    },

    deleteItem() {
      this.$inertia.delete(route('events.statuses.destroy', { event: this.event.id, status: this.status.id }), {
        onSuccess: () => {
          this.$notify.success({
            title: 'Успешно',
            message: 'Статус был удален!',
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
