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
        title="Удаление блока"
        width="30%"
        center
      >
        <span>Вы уверены, что хотите удалить данный блок?</span>
        <template #footer>
          <el-button @click="askDeleteConfirm = false">
            Отмена
          </el-button>
          <el-button
            type="danger"
            @click="deleteBlock"
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
import InfoBlockForm from '@/components/Site/InfoBlockForm';

export default {
  name: 'InfoBlockEdit',
  components: { InfoBlockForm, SiteLayout },
  props: {
    infoBlock: Object,
  },
  data() {
    return ({
      askDeleteConfirm: false,
      isSaving: false,
    });
  },

  mounted() {
    this.$refs.blockForm.initForm();
  },

  methods: {
    backClick() {
      this.$inertia.visit(route('site.info-blocks'));
    },

    deleteBlock() {
      this.askDeleteConfirm = false;
      this.$inertia.delete(route('site.info-blocks.destroy', this.infoBlock.id), {
        onSuccess: () => {
          this.$notify.success({
            title: 'Успешно',
            message: 'Блок был удален!',
          });
        },
      });
    },

    save() {
      this.$refs.blockForm.submit();
    },
  },
};
</script>

<style scoped>

</style>
