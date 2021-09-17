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
      <slider-form
        ref="form"
        :slider="slider"
        :slider-type="sliderType"
        class="w-50"
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
import SliderForm from '@/components/Site/SliderForm';

export default {
  name: 'SliderCreate',
  components: { SiteLayout, SliderForm },
  props: {
    slider: Object,
    sliderType: Number,
  },

  methods: {
    backClick() {
      // eslint-disable-next-line no-unused-vars
      const [__, action] = this.route().current().split('.');

      this.$inertia.visit(route(`site.${action}`));
    },

    create() {
      this.$refs.form.submit();
    },
  },
};
</script>

<style scoped>

</style>
