<template>
  <site-layout>
    <div class="mb-3">
      <div class="d-flex justify-content-between flex-nowrap">
        <el-input
          class="mr-3"
          placeholder="Быстрый поиск"
          prefix-icon="el-icon-search"
        />
        <el-button
          type="primary"
          icon="el-icon-plus"
          @click="createPage"
        >
          Добавить
        </el-button>
      </div>
    </div>

    <div class="bg-white shadow-sm">
      <pages-table
        :items="pages.data"
        :page-type="pageType"
        :page-types="pageTypes"
      />

      <ui-pagination
        :max="pages.last_page"
        :page="pages.current_page"
      />
    </div>
  </site-layout>
</template>

<script>
import SiteLayout from '@/Layouts/SiteLayout';
import PagesTable from '@/components/Site/PagesTable';
import UiPagination from '@/components/UI/UiPagination';

export default {
  name: 'PageIndex',
  components: { UiPagination, PagesTable, SiteLayout },
  props: {
    pages: Array,
    pageType: String,
    pageTypes: Array,
  },

  methods: {
    createPage() {
      this.$inertia.visit(this.route(`site.${this.pageType}.create`));
    },
  },
};
</script>

<style scoped>

</style>
