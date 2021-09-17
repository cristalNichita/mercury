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
      <mailing-form
        ref="form"
        :mailing="mailing"
        :events="events"
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
        title="Удаление шаблона рассылки"
        width="30%"
        center
      >
        <span>Вы уверены, что хотите удалить данный шаблон расслки?</span>
        <template #footer>
          <el-button @click="askDeleteConfirm = false">
            Отмена
          </el-button>
          <el-button
            type="danger"
            @click="deleteDirectory"
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
import MailingForm from '@modules/Mailing/Resources/MailingForm';

export default {
  name: 'MailingEdit',
  components: { MailingForm, SettingsLayout },
  props: {
    mailing: Object,
    events: Object,
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
      this.$inertia.visit(route('mailing.index'));
    },

    deleteDirectory() {
      this.$inertia.delete(route('mailing.destroy', this.mailing.id), {
        onSuccess: () => {
          this.$notify.success({
            title: 'Успешно',
            message: 'Шаблон рассылки был удален!',
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
