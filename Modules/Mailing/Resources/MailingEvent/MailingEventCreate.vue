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
      <mailing-event-form
        ref="form"
        :event="event"
        :handlings="handlings"
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
import MailingEventForm from '@modules/Mailing/Resources/MailingEventForm';

export default {
  name: 'MailingEventCreate',
  components: { MailingEventForm, SettingsLayout },
  props: {
    event: Object,
    handlings: Array,
  },

  methods: {
    backClick() {
      this.$inertia.visit(route('events.index'));
    },

    create() {
      this.$refs.form.submit();
    },
  },
};
</script>

<style scoped>

</style>
