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
import MailingEventStatusForm from '@modules/Mailing/Resources/MailingEventStatusForm';

export default {
  name: 'MailingEventStatusCreate',
  components: { MailingEventStatusForm, SettingsLayout },
  props: {
    event: Object,
    status: Object,
  },

  methods: {
    backClick() {
      this.$inertia.visit(route('events.edit', this.event.id));
    },

    create() {
      this.$refs.form.submit();
    },
  },
};
</script>

<style scoped>

</style>
