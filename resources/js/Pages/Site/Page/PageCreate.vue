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
        :user="user"
        :page="pageData"
        :page-type="pageType"
        :page-types="pageTypes"
        :categories="categories"
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
  </site-layout>
</template>

<script>
import SiteLayout from '@/Layouts/SiteLayout';
import PageForm from '@/components/Site/PageForm';

export default {
  name: 'PageCreate',
  components: { PageForm, SiteLayout },
  props: {
    page: Object,
    pageType: String,
    pageTypes: Object,
    categories: Object,
  },

  computed: {
    pageData() {
      return this.page;
    },
    user() {
      return this.$page.props.user;
    },
  },

  mounted() {
    this.$refs.form.initForm();
  },

  methods: {
    backClick() {
      this.$inertia.visit(route(`site.${this.pageType}`));
    },

    create() {
      this.$refs.form.submit();
    },
  },
};
</script>

<style scoped>

</style>
