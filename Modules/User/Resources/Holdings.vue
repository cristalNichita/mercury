<template>
  <user-layout>
    <div class="mb-3">
      <div class="d-flex justify-content-between flex-nowrap">
        <el-input
          v-model="quickFilter"
          placeholder="Быстрый поиск по названию, ИНН, телефону и email"
          prefix-icon="el-icon-search"
          @input="quickSearch"
        />

        <el-button
          type="primary"
          class="ml-3"
          icon="el-icon-plus"
          @click="addCompany"
        >
          Контрагент
        </el-button>

        <el-button
          type="primary"
          class="ml-3"
          icon="el-icon-plus"
          @click="addContact"
        >
          Контакт
        </el-button>
      </div>
    </div>
    <div class="bg-white shadow-sm">
      <holdings-table
        v-loading="loading"
        :holdings="preparedHoldings"
        @update:loading="loading = $event"
      >
        <!--        <el-table-column-->
        <!--          prop="raw"-->
        <!--          label="Дата"-->
        <!--        >-->
        <!--          <template #default="scope">-->
        <!--            <pre>-->
        <!--            {{ scope.row }}-->
        <!--            </pre>-->

        <!--          </template>-->
        <!--        </el-table-column>-->
      </holdings-table>

      <ui-pagination
        :max="holdings.last_page"
        :page="holdings.current_page"
        @update:loading="loading = $event"
      />
    </div>
  </user-layout>
</template>

<script>
import UserLayout from '@/Layouts/UserLayout';
import HoldingsTable from '@modules/User/Resources/HoldingsTable';
import UiPagination from '@/components/UI/UiPagination';

export default {
  name: 'Parameters',
  components: { HoldingsTable, UserLayout, UiPagination },
  props: {
    holdings: {
      type: Object,
      required: true,
    },
    filter: {
      type: Object,
      default: () => {
      },
    },
  },
  data() {
    return {
      loading: false,
      // Нихера не работает хер знает почему - в filter лежит какойто Proxy
      quickFilter: this.filter?.filter?.title ?? '',
      debounceTimeout: 0,
    };
  },
  computed: {
    preparedHoldings() {
      const result = this.holdings.data;

      let i = 1;
      result.map((holding) => {
        holding.children = [];
        holding.row_id = i++;
        holding.row_type = 's-grid';
        holding.route = 'users.holdings.show';

        if (holding.companies) {
          holding.companies.forEach(((company) => {
            company.row_id = i++;
            company.row_type = 's-cooperation';
            company.route = 'users.company.show';
            company.type = company.type_1c;
            holding.children.push(company);
          }));
        }

        if (holding.contacts) {
          holding.contacts.forEach(((contact) => {
            contact.row_id = i++;
            contact.row_type = 'user-solid';
            contact.type = contact.position;
            contact.route = 'users.contacts.show';
            contact.is_owner = holding.contact_id === contact.id
            holding.children.push(contact);
          }));
        }

        return holding;
      });
      return result;
    },
  },
  methods: {
    addContact() {
      this.$inertia.visit(route('users.contacts.create'));
    },
    addCompany() {
      this.$inertia.visit(route('users.company.create'));
    },
    quickSearch() {
      clearTimeout(this.debounceTimeout);
      this.debounceTimeout = setTimeout(() => this.doQuickSearch(), 300);
    },
    doQuickSearch() {
      let data = {};
      if (this.quickFilter) {
        data = { filter: { title: this.quickFilter } };
      }

      this.$inertia.replace(route(route().current()), {
        method: 'get',
        data,
        replace: false,
        preserveState: true,
        preserveScroll: false,
        onBefore: () => {
          this.loading = true;
        },
        onFinish: () => {
          this.loading = false;
        },
      });
    },
  },
};
</script>

<style scoped>

</style>
