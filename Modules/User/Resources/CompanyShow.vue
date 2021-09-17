<template>
  <div>
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

        <div class="text-truncate h4 line-height-1 m-0">
          Контрагент ID {{ company.id }}
        </div>
      </div>
    </div>

    <div
      v-loading="loading"
      class="bg-white shadow-sm p-3"
    >
      <div class="row align-items-start mb-4">
        <div class="col">
          <div class="h2">
            {{ company.name }}
          </div>

          <div class="mb-2">
            Тип: {{ company.type_1c }}
          </div>

          <div class="mb-2">
            ИНН: {{ company.inn }}
          </div>

          <div class="mb-3">
            КПП: {{ company.kpp }}
          </div>

          <div class="mb-3">
            Холдинг:
            <inertia-link :href="route('users.holdings.show', company.holding_id)">
              {{ company.holding.name }}
            </inertia-link>
          </div>
          <template v-if="company.params">
            <div class="h4 mb-3">
              Контакты
            </div>
            <contact-params :params="company.params" />
          </template>
        </div>

        <div class="col-auto">
          <div class="mb-4">
            GUID: {{ company.guid }}<br>GUID Сайт: {{ company.guid_site }}
          </div>

          <div v-if="company.user">
            Создан личный кабинет:
            <div class="text-success">
              {{ $filters.timeFormat(company.user.created_at) }}
            </div>
          </div>
        </div>
      </div>

      <template v-if=" company.bank_requisites ">
        <div class="h4 mb-3">
          Банковские реквизиты
        </div>
        <company-bank-requisites-table
          :bankRequisites="company.bank_requisites"
          :company_id="company.id"
        />
      </template>

      <div class="h4 mb-3">
        Заказы
      </div>
      <div class="bg-white shadow-sm">
        <orders-table
          v-loading="loading"
          :orders="orders.data"
          @update:loading="loading = $event"
        />
        <ui-pagination
          :max="orders.meta.last_page"
          :page="orders.meta.current_page"
          @update:loading="loading = $event"
        />
      </div>
    </div>
  </div>
</template>

<script>
import UserLayout from '@/Layouts/UserLayout';
import ContactParams from '@modules/User/Resources/ContactParams';
import UiPagination from '@/components/UI/UiPagination';
import OrdersTable from '@modules/Order/Resources/OrdersTable';
import CompanyBankRequisitesTable from "@modules/User/Resources/CompanyBankRequisitesTable";

export default {
  name: 'Users',
  components: { ContactParams, UiPagination, OrdersTable, CompanyBankRequisitesTable },
  layout: (h, page) => h(UserLayout, [page]),
  props: {
    company: {
      type: Object,
      required: true,
    },
    orders: {
      type: Object,
      required: true,
    },
  },
  data() {
    return ({
      loading: false,
    });
  },
  methods: {
    declination(value, words) {
      value = Math.abs(value) % 100;
      const num = value % 10;
      if (value > 10 && value < 20) return words[2];
      if (num > 1 && num < 5) return words[1];
      if (num == 1) return words[0];
      return words[2];
    },
    buildForm() {
      return this.$inertia.form({
        name: this.company.name,
        company: this.company.inn,
        ogrn: this.company.ogrn,
        kpp: this.company.kpp,
        city: this.company.city,
        street: this.company.street,
        postal_code: this.company.postal_code,
        house: this.company.house,
        office: this.company.office,
        checking_account: this.company.checking_account,
        corporate_account: this.company.corporate_account,
      });
    },
    backClick() {
      // eslint-disable-next-line no-restricted-globals
      history.go(-1);
    },
    validate() {
      return this.$refs.user_create_form.validate();
    },
    delete() {
      this.form.delete(route('users.company.delete', this.company.id), {
        onBefore: () => {
          this.loading = true;
        },
        onSuccess: () => {
          this.$notify.success({
            title: 'Успешно',
            message: 'Компания успешно удалена',
          });
        },
        onError: () => {
          this.$notify.error({
            title: 'Ошибка',
            message: 'При удалении произошла ошибка',
          });
        },
        preserveState: false,
      });
    },
    save() {
      this.validate().then(() => {
        this.form.put(route('users.company.update', this.company.id), {
          onBefore: () => {
            this.loading = true;
          },
          onSuccess: () => {
            this.loading = false;
            this.$notify.success({
              title: 'Успешно',
              message: 'Компания успешно обновлена',
            });
          },
          onError: () => {
            this.loading = false;
            this.$notify.error({
              title: 'Ошибка',
              message: 'При сохранении произошла ошибка',
            });
          },
          onFinish: () => {
            this.loading = false;
          },
          preserveState: true,
        });
      }).catch((err) => {
        console.log(err);
        this.loading = false;
        this.$notify.error({
          title: 'Ошибки в форме',
          message: 'Заполните необходимые поля',
        });
      });
    },
  },
};
</script>

<style scoped>

</style>
