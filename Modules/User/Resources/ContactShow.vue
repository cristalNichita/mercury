<template>
  <user-layout>
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
          Контактное лицо ID {{ contact.id }}
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
            {{ contact.name }}
          </div>

          <div class="mb-3">
            Должность: {{ contact.position }}
          </div>
          <div class="mb-3">
            Холдинг:
            <inertia-link
              :href="route('users.holdings.show', contact.holding_id)">
              {{ contact.holding.name }}
            </inertia-link>
          </div>
          <template v-if=" contact.params ">
            <div class="h4 mb-3">
              Контакты
            </div>
            <contact-params :params="contact.params"/>
          </template>
        </div>

        <div class="col-auto">
          <div class="mb-4">
            GUID: {{ contact.guid }}<br>GUID Сайт: {{ contact.guid_site }}
          </div>

          Личный кабинет:
          <div v-if="contact.user">
            <div class="text-success">
              Создан {{ $filters.timeFormat(contact.user.created_at) }}
            </div>
            Пользователь:
            <inertia-link :href="route('users.edit', contact.user.id)">
              {{ contact.user.name }}
            </inertia-link>
            <div v-if="contact.user.email">
              Email: <a :href="`mailto:${contact.user.email}`">
              {{ contact.user.email }}
            </a>
            </div>
            <div v-if="contact.user.phone">
              Телефон: <a
              :href="`tel:+${contact.user.phone.replace(/\D+/gm, '')}`"
            >
              {{ contact.user.phone }}
            </a>
            </div>
          </div>
          <div v-else >
            <div v-if="inviteUserErrors.length">
              <div class="text-danger"
                   v-for="error in inviteUserErrors">
                {{ error }}
              </div>
            </div>
            <div v-else >
              <el-button
                type="primary"
                icon="el-icon-plus"
                @click="inviteUser"
              >
                Пригласить пользователя
              </el-button>
            </div>
          </div>
        </div>
      </div>
      <div class="h4 mb-3">
        Заказы
      </div>
      <div class="bg-white shadow-sm">
        <orders-table
          v-loading="loading"
          :orders="orders.data"
          :excludeContactColumn="true"
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
import ContactParams from '@modules/User/Resources/ContactParams';
import UiPagination from '@/components/UI/UiPagination';
import OrdersTable from '@modules/Order/Resources/OrdersTable';

export default {
  name: 'ContactShow',
  components: {ContactParams, UserLayout, UiPagination, OrdersTable},
  props: {
    contact: {
      type: Object,
      required: true,
    },
    inviteUserErrors: {
      type: Array,
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
    };
  },
  methods: {
    backClick() {
      // eslint-disable-next-line no-restricted-globals
      history.go(-1);
    },
    inviteUser() {
      this.loading = true;

      this.$inertia.post(route('users.contacts.invite', this.contact.id), {}, {
        onSuccess: () => {
          this.$notify.success({
            title: 'Успешно',
            message: 'Приглашение успешно отправленно',
          });
        },
        onError: (errors) => {
          Object.values(errors).forEach(value => {
            this.$notify.error({
              title: 'Ошибка',
              message: value,
            });
          });
        },
        onFinish: () => {
          this.loading = false;
        },
      });
    },
  },
};
</script>
