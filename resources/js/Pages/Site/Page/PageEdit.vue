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
      <page-form
        ref="form"
        class="w-50"
        :page="pageData"
        :page-type="pageType"
        :page-types="pageTypes"
        :categories="categories"
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
    </div>

    <el-dialog
      v-model="askDeleteConfirm"
      title="Удаление Слайдера"
      width="30%"
      center
    >
      <span>Вы уверены, что хотите удалить данныую страницу?</span>
      <template #footer>
        <el-button @click="askDeleteConfirm = false">
          Отмена
        </el-button>
        <el-button
          type="danger"
          @click="deletePage"
        >
          Да, удалить
        </el-button>
      </template>
    </el-dialog>
  </site-layout>
</template>

<script>
import SiteLayout from '@/Layouts/SiteLayout';
import PageForm from '@/components/Site/PageForm';

export default {
  name: 'PageEdit',
  components: { PageForm, SiteLayout },
  props: {
    page: Object,
    pageType: String,
    pageTypes: Object,
    categories: Object,
  },
  data() {
    return ({
      askDeleteConfirm: false,
      isSaving: false,
    });
  },

  computed: {
    pageData() {
      return this.page;
    },
  },

  mounted() {
    this.$refs.form.initForm();
  },

  methods: {
    backClick() {
      if (this.pageType === 'info') {
        history.back();
      }
      this.$inertia.visit(route(`site.${this.pageType}`));
    },

    deletePage() {
      this.askDeleteConfirm = false;
      this.$inertia.delete(route(`site.${this.pageType}.destroy`, this.pageData.id), {
        onSuccess: () => {
          this.$notify.success({
            title: 'Успешно',
            message: 'Удаление было совершено успешно!',
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
