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
          @click="createSlider"
        >
          Добавить
        </el-button>
      </div>
    </div>

    <div class="bg-white shadow-sm">
      <sliders-table
        :sliders="sliders.data"
      />
      <ui-pagination
        :max="sliders.last_page"
        :page="sliders.current_page"
      />
    </div>
  </site-layout>
</template>

<script>
import SiteLayout from '@/Layouts/SiteLayout';
import SlidersTable from '@/components/Site/SlidersTable';
import UiPagination from '@/components/UI/UiPagination';

export default {
  name: 'SliderIndex',
  components: { UiPagination, SlidersTable, SiteLayout },
  props: {
    sliders: Array,
  },

  methods: {
    createSlider() {
      // eslint-disable-next-line no-unused-vars
      const [__, action] = this.route().current().split('.');

      this.$inertia.visit(route(`site.${action}.create`));
    },
  },
};
</script>
