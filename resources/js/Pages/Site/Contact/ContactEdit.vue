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
        <span>Вы уверены, что хотите удалить данный слайдер?</span>
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
  </site-layout>
</template>

<script>
import SiteLayout from '@/Layouts/SiteLayout';
import ContactForm from '@/components/Site/ContactForm';

export default {
  name: 'ContactEdit',
  components: { ContactForm, SiteLayout },
  props: {
    contact: Object,
  },
  data() {
    return ({
      askDeleteConfirm: false,
      isSaving: false,
    });
  },

  mounted() {
    this.$refs.form.initForm();
  },

  methods: {
    backClick() {
      this.$inertia.visit(route('site.contacts'));
    },

    deleteItem() {
      this.askDeleteConfirm = false;
      this.$inertia.delete(route('site.contacts.destroy', this.contact.id), {
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
