<template>
  <user-layout>
    <div class="mb-3">
      <div class="d-flex flex-nowrap align-items-center">
        <el-button
          type="primary"
          icon="el-icon-arrow-left"
          class="mr-4"
          @click="backClick"
        >
          Назад
        </el-button>

        <div class="text-truncate h4 line-height-1 m-0">
          Холдинг ID {{ holding.id }}
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
            Название: {{ holding.name }}
          </div>
        </div>
      </div>

      <el-form
        ref="owner-update-form"
        v-loading="updateOwnerLoading"
        :model="updateOwnerForm"
        :rules="rules"
        label-width="160px"
        @submit.native.prevent="updateOwnerSubmit"
      >
        <el-form-item
          label="Основной контакт"
          prop="owner_id"
        >
          <el-select
            v-model="updateOwnerForm.owner_id"
            filterable
            clearable
            class="w-100"
            placeholder="Контакт не выбран"
          >
            <el-option
              v-for="item in holding.contacts"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button
            type="primary"
            @click="updateOwnerSubmit"
          >
            Сохранить
          </el-button>
        </el-form-item>
      </el-form>

      <el-form
        v-loading="addCompanyLoading"
        label-width="240px"
      >
        <el-form-item
          label="Быстрое добавление компании"
        >
          <ui-dadata-company @select="addCompany" />
        </el-form-item>
      </el-form>

      <div class="h4 mb-3">
        Контакты
      </div>
      <div class="bg-white shadow-sm">
        <holding-contacts-table
          v-loading="loading"
          :contacts="holding.contacts"
        />
      </div>
      <div class="h4 my-3">
        Компании
      </div>
      <div class="bg-white shadow-sm">
        <holding-companies-table
          v-loading="loading"
          :companies="holding.companies"
        />
      </div>
      <div class="h4 my-3">
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
  </user-layout>
</template>

<script>
import UserLayout from '@/Layouts/UserLayout';
import UiPagination from '@/components/UI/UiPagination';
import OrdersTable from '@modules/Order/Resources/OrdersTable';
import HoldingContactsTable from '@modules/User/Resources/HoldingContactsTable';
import HoldingCompaniesTable
  from '@modules/User/Resources/HoldingCompaniesTable';
import UiDadataCompany from '@/components/UI/UiDadataCompany';

export default {
  name: 'ContactShow',
  components: {
    UserLayout,
    UiPagination,
    OrdersTable,
    HoldingContactsTable,
    HoldingCompaniesTable,
    UiDadataCompany,
  },
  props: {
    holding: {
      type: Object,
      required: true,
    },
    orders: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      loading: false,
      updateOwnerLoading: false,
      updateOwnerForm: this.$inertia.form({
        owner_id: this.holding.contact_id,
      }),
      addCompanyLoading: false,
    };
  },
  methods: {
    backClick() {
      // eslint-disable-next-line no-restricted-globals
      history.go(-1);
    },
    updateOwnerSubmit() {
      this.updateOwnerLoading = true;

      this.updateOwnerForm.post(route('users.holdings.updateOwner', this.holding.id), {
        preserveScroll: true,
        onSuccess: () => {
          this.$notify.success({
            title: 'Успешно',
            message: 'Основной контакт успешно обновлен',
          });
        },
        onError: (errors) => {
          Object.values(errors).forEach((value) => {
            this.$notify.error({
              title: 'Ошибка',
              message: value,
            });
          });
        },
        onFinish: () => {
          this.updateOwnerLoading = false;
        },
      });
    },
    addCompany(item) {
      this.addCompanyLoading = true;

      const data = {
        inn: item.data.inn,
        kpp: item.data.kpp,
        ogrn: item.data.ogrn,
        name: item.name,
      };

      this.$inertia.post(route('users.holdings.addCompany', this.holding.id), data, {
        onSuccess: () => {
          this.$notify.success({
            title: 'Успешно',
            message: 'Компания успешно добавлена',
          });
          this.query = null;
        },
        onError: (errors) => {
          Object.values(errors).forEach((value) => {
            this.$notify.error({
              title: 'Ошибка',
              message: value,
            });
          });
        },
        onFinish: () => {
          this.addCompanyLoading = false;
        },
      });
    },
  },
};
</script>
