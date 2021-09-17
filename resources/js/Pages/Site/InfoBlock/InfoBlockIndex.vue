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
          @click="createBlock"
        >
          Добавить
        </el-button>
      </div>
    </div>

    <div class="bg-white shadow-sm">
      <info-blocks-table
        :blocks="infoBlocks.data"
      />
      <ui-pagination
        :max="infoBlocks.last_page"
        :page="infoBlocks.current_page"
      />
    </div>
  </site-layout>
</template>

<script>
import SiteLayout from '@/Layouts/SiteLayout';
import InfoBlocksTable from '@/components/Site/InfoBlocksTable';
import UiPagination from '@/components/UI/UiPagination';

export default {
  name: 'InfoBlockIndex',
  components: { UiPagination, InfoBlocksTable, SiteLayout },
  props: {
    infoBlocks: Array,
  },

  methods: {
    createBlock() {
      this.$inertia.visit(route('site.info-blocks.create'));
    },
  },
};
</script>

<style scoped>

</style>
