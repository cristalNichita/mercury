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
        ref="sliderForm"
        class="w-50"
        :slider="slider"
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
      <span>Вы уверены, что хотите удалить данный слайдер?</span>
      <template #footer>
        <el-button @click="askDeleteConfirm = false">
          Отмена
        </el-button>
        <el-button
          type="danger"
          @click="deleteSlider"
        >
          Да, удалить
        </el-button>
      </template>
    </el-dialog>
  </site-layout>
</template>

<script>
import SiteLayout from '@/Layouts/SiteLayout';
import SliderForm from '@/components/Site/SliderForm';

export default {
  name: 'SliderEdit',
  components: { SliderForm, SiteLayout },
  props: {
    slider: Object,
  },
  data() {
    return ({
      slider_state: 'show',
      askDeleteConfirm: false,
      isSaving: false,
    });
  },

  mounted() {
    this.$refs.sliderForm.initForm();
  },

  methods: {
    backClick() {
      // eslint-disable-next-line no-unused-vars
      const [__, action] = this.route().current().split('.');

      this.$inertia.visit(route(`site.${action}`));
    },

    deleteSlider() {
      this.askDeleteConfirm = false;
      // eslint-disable-next-line no-unused-vars
      const [__, action] = this.route().current().split('.');

      this.$inertia.delete(route(`site.${action}.destroy`, this.slider.id), {
        onSuccess: () => {
          this.$notify.success({
            title: 'Успешно',
            message: 'Слайдер был удален!',
          });
        },
      });
    },

    save() {
      this.$refs.sliderForm.submit();
    },
  },
};
</script>

<style scoped>

</style>
