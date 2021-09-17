<template>
  <settings-layout>
    <div class="h3">
      Список событий для рассылки
    </div>
    <div class="mb-3">
      <div class="d-flex justify-content-between flex-nowrap">
        <el-button
          type="success"
          icon="el-icon-message"
          @click="openMailings"
        >
          Шаблоны рассылок
        </el-button>

        <el-button
          type="primary"
          icon="el-icon-plus"
          @click="createEvent"
        >
          Добавить
        </el-button>
      </div>
    </div>

    <div class="bg-white shadow-sm">
      <mailing-event-table
        :events="events"
      />
    </div>
  </settings-layout>
</template>

<script>
import SettingsLayout from '@/Layouts/SettingsLayout';
import MailingEventTable from '@modules/Mailing/Resources/MailingEventTable';

export default {
  name: 'MailingEventIndex',
  components: { MailingEventTable, SettingsLayout },
  props: {
    events: Array,
  },

  methods: {
    createEvent() {
      this.$inertia.visit(route('events.create'));
    },
    openMailings() {
      this.$inertia.visit(route('mailing.index'));
    },
  },
};
</script>

<style scoped>

</style>
