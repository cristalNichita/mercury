<template>
  <settings-layout>
    <div class="h3">
      Шаблоны рассылок
    </div>
    <div class="mb-3">
      <div class="d-flex justify-content-between flex-nowrap">
        <el-button
          type="success"
          icon="el-icon-bell"
          @click="openEvents"
        >
          Список событий
        </el-button>

        <el-button
          type="primary"
          icon="el-icon-plus"
          @click="createMailing"
        >
          Добавить
        </el-button>
      </div>
    </div>

    <div class="bg-white shadow-sm">
      <mailing-table
        :mailings="mailings"
      />
    </div>
  </settings-layout>
</template>

<script>
import SettingsLayout from '@/Layouts/SettingsLayout';
import MailingTable from '@modules/Mailing/Resources/MailingTable';

export default {
  name: 'MailingIndex',
  components: { MailingTable, SettingsLayout },
  props: {
    mailings: Array,
  },

  methods: {
    createMailing() {
      this.$inertia.visit(route('mailing.create'));
    },
    openEvents() {
      this.$inertia.visit(route('events.index'));
    },
  },
};
</script>

<style scoped>

</style>
