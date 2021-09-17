<template>
  <div>
    <el-autocomplete
      v-model="name"
      :disabled="disabled"
      class="inline-input w-100 dadata-search"
      :fetch-suggestions="querySearchCompany"
      placeholder="Введите название или инн"
      @select="handleSelectCompany"
    >
      <template #default="{ item }">
        <div
          :class="{
            'dadata-search__item': true,
            'dadata-search__item--liquidated': item.liquidated,
          }"
        >
          <div class="dadata-search__item-title">
            <span class="dadata-search__item-title-value">
              {{ item.value }}
            </span>
            <span v-if="item.liquidated"> Ликвидирован</span>
          </div>
          <br>
          <div class="dadata-search__item-detail">
            ИНН {{ item.inn }}, {{ item.address }} <br>
            <div
              v-if="item.management"
              class="mt-1"
            >
              {{ item.management.name }}
            </div>
          </div>
        </div>
      </template>
    </el-autocomplete>
  </div>
</template>
<script>
/**
 * Компонент получения информации о компаниях из dadata
 *
 * Получае детальную информаццю от сервиса dadata о компаниях,
 * по ИНН, имени, адресу.
 *
 * @event select
 * @property {object} item - Информация о выбранной компании
 *
 * @event update:loading
 * @property {boolean} loading - флаг загрузки страницы
 *
 * @example <ui-dadata-company @select="select"/>
 */
export default {
  name: 'UiDadataCompany',
  props: {
    disabled: { type: Boolean, default: false },
  },
  data() {
    return {
      name: '',
    };
  },
  computed: {
    dadataToken() {
      return this.$page.props.dadata_token;
    },
  },
  methods: {
    querySearchCompany(query, callBack) {
      this.$store.dispatch('dadata/company', {
        query,
        token: this.dadataToken,
      }).then((suggestions) => {
        if (suggestions) {
          callBack(suggestions);
        }
      }).catch(() => {
        this.$notify.error({
          title: 'Ошибка',
          message: 'При работе произошла ошибка',
        });
      });
    },
    handleSelectCompany(item) {
      this.$emit('select', item);
    },
  },
};
</script>

<style scoped lang="scss">
.dadata-search {
  &__item--liquidated {
    & .dadata-search__item-title-value,
    & .dadata-search__item-detail {
      text-decoration: line-through;
      text-decoration-color: red;
    }
  }

  &__item {
    line-height: 12px;
    margin-bottom: 15px;
  }

  &__item-title {
    font-weight: bold;
  }

  &__item-detail {
    font-size: 12px;
    color: #989898;
  }
}
</style>
