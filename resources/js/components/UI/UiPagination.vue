<template>
  <el-pagination
    v-if="max>1"
    class="py-3"
    background
    :page-count="max"
    :current-page="page"
    layout="prev, pager, next"
    @current-change="changePage"
  />
</template>

<script>
/**
   * Компонент автоматической пагинации
   *
   * Выводит пагинацию и осуществляет перезагрузку текущей страницы с добавлением
   * get параметра page
   *
   * Для отмены автоматического перехода используйте параметр no-visit получить
   * страницу можно по событию @change
   *
   * @event change
   * @property {number} page - выбранная страница
   *
   * @event update:loading
   * @property {boolean} loading - флаг загрузки страницы
   *
   * @example <ui-pagination :max="products.to" :page="products.current_page"/>
   */
export default {
  name: 'UiPagination',
  props: {
    max: {
      type: Number,
      default: 1,
    },
    page: {
      type: Number,
      default: 1,
    },
    /**
       * Не производить переход
       */
    noVisit: {
      type: Boolean,
      default: false,
    },
  },
  methods: {
    changePage(page) {
      /**
         * Событие изменение страницы
         *
         * @event change
         * @type {number}
         */
      this.$emit('change', page);

      if (this.noVisit) {
        return;
      }

      this.$inertia.replace(route(route().current(), route().params), {
        method: 'get',
        data: { page },
        replace: false,
        preserveState: true,
        preserveScroll: false,
        onBefore: () => {
          this.$emit('update:loading', true);
        },
        onFinish: () => {
          this.$emit('update:loading', false);
        },
      });
    },
  },
};
</script>

<style scoped>

</style>
