<template>
  <site-layout>
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
      <contact-form
        ref="form"
        :contact="contact"
      />

      <el-row
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
  </site-layout>
</template>

<script>
import SiteLayout from '@/Layouts/SiteLayout';
import ContactForm from '@/components/Site/ContactForm';

export default {
  name: 'ContactCreate',
  components: { ContactForm, SiteLayout },
  props: {
    contact: Object,
  },

  methods: {
    backClick() {
      this.$inertia.visit(route('site.contacts'));
    },

    create() {
      this.$refs.form.submit();
    },
  },
};
</script>

<style scoped>

</style>
