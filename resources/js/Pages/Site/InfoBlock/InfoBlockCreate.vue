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
      <info-block-form
        ref="blockForm"
        class="w-50"
        :block="infoBlock"
      />

      <el-row
        type="flex"
        class="row-bg mt-4"
      >
        <el-button
          type="primary"
          @click="createBlock"
        >
          Создать
        </el-button>
      </el-row>
    </div>
  </site-layout>
</template>

<script>
import SiteLayout from '@/Layouts/SiteLayout';
import InfoBlockForm from '@/components/Site/InfoBlockForm';

export default {
  name: 'InfoBlockCreate',
  components: { InfoBlockForm, SiteLayout },
  props: {
    infoBlock: Object,
  },

  methods: {
    backClick() {
      this.$inertia.visit(route('site.info-blocks'));
    },

    createBlock() {
      this.$refs.blockForm.submit();
    },
  },
};
</script>

<style scoped>

</style>
