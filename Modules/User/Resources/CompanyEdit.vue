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
          <template v-if="company.id">
            Редактирование контрагента ID {{ company.id }}
          </template>
          <template v-else>
            Новый контрагент
          </template>
        </div>
      </div>
    </div>

    <div class="bg-white shadow-sm p-3">
      <el-form
        ref="company_edit_form"
        v-loading="form.processing"
        :model="form"
        :rules="rules"
        label-width="120px"
        @submit.native.prevent="submit"
      >
        <el-form-item
          label="Холдинг"
          prop="holding_id"
        >
          <el-select
            v-model="form.holding_id"
            filterable
            remote
            clearable
            class="w-100"
            placeholder="Будет создан новый Холдинг"
            :remote-method="getHoldings"
            :loading="loadingHoldings"
          >
            <el-option
              v-for="item in holdings"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>

        <hr>

        <el-form-item
          label="Тип"
          prop="type"
        >
          <el-radio
            v-model="form.type"
            :label="0"
          >
            Физическое лицо
          </el-radio>
          <el-radio
            v-model="form.type"
            :label="1"
          >
            Юридическое лицо / ИП

            <span
              v-if="form.type_1c"
              class="text-muted"
            >
              ({{ form.type_1c }})
            </span>
          </el-radio>
        </el-form-item>

        <el-form-item
          v-if="form.type===1"
          label="Поиск"
        >
          <ui-dadata-company
            :company-name="company.name"
            class="mb-4"
            @select="selectInn"
          />
        </el-form-item>

        <el-form-item
          label="Наименование"
          prop="name"
        >
          <el-input
            id="name"
            v-model="form.name"
          />
        </el-form-item>

        <el-form-item
          label="ИНН"
          prop="inn"
        >
          <el-input
            id="inn"
            v-model="form.inn"
          />
        </el-form-item>

        <el-form-item
          v-if="form.type===1"
          label="КПП"
          prop="kpp"
        >
          <el-input
            id="kpp"
            v-model="form.kpp"
          />
        </el-form-item>

        <el-form-item
          v-if="form.type===1"
          label="ОГРН"
          prop="ogrn"
        >
          <el-input
            id="ogrn"
            v-model="form.ogrn"
          />
        </el-form-item>

        <div class="h4">
          Банковские реквизиты
        </div>

<!--        <el-form-item-->
<!--          v-if="form.type===1"-->
<!--          label="Поиск"-->
<!--        >-->
<!--          <ui-dadata-inn-->
<!--            :company-name="company.name"-->
<!--            class="mb-4"-->
<!--            @select="selectBik"-->
<!--          />-->
<!--        </el-form-item>-->

        <el-form-item
          label="БИК"
          prop="bank_bik"
        >
          <el-input
            id="bank_bik"
            v-model="form.bank_bik"
          />
        </el-form-item>

        <el-form-item
          label="Банк"
          prop="bank_name"
        >
          <el-input
            id="bank_name"
            v-model="form.bank_name"
          />
        </el-form-item>

        <el-form-item
          label="Корр. счет"
          prop="bank_kor"
        >
          <el-input
            id="bank_kor"
            v-model="form.bank_kor"
          />
        </el-form-item>

        <el-form-item
          label="Расчетный счет"
          prop="bank_invoice"
        >
          <el-input
            id="bank_invoice"
            v-model="form.bank_invoice"
          />
        </el-form-item>

        <div class="h4">
          Контакты
        </div>

        <el-form-item
          label="Телефон"
          prop="phone"
        >
          <el-input
            v-model="form.phone"
            v-maska="'+7 (###) ###-##-##'"
            placeholder="+7 (___) ___-__-__"
          />
        </el-form-item>

        <el-form-item
          label="Email"
          prop="email"
        >
          <el-input
            v-model="form.email"
            type="email"
          />
        </el-form-item>

        <el-form-item
          label="Юр. адрес"
          prop="u_address"
        >
          <el-input
            v-model="form.u_address"
          />
        </el-form-item>

        <el-form-item
          label="Факт. адрес"
          prop="f_address"
        >
          <el-input
            v-model="form.f_address"
          />
        </el-form-item>

        <el-form-item>
          <el-button
            type="primary"
            @click="submit"
          >
            Сохранить
          </el-button>
        </el-form-item>
      </el-form>
    </div>
  </user-layout>
</template>

<script>
import UserLayout from '@/Layouts/UserLayout';
import UiDadataCompany from '@/components/UI/UiDadataCompany';

export default {
  name: 'CompanyEdit',
  components: { UserLayout, UiDadataCompany },
  props: {
    company: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      loading: false,

      holdings: [],
      loadingHoldings: false,

      form: this.$inertia.form({
        holding_id: this.company.holding_id,
        type: this.company.type ?? 1,
        type_1c: this.company.type_1c,
        name: this.company.name,
        inn: this.company.inn,
        kpp: this.company.kpp,
        ogrn: this.company.ogrn,
        phone: this.company.phone,
        email: this.company.email,
        u_address: this.company.u_address,
        f_address: this.company.f_address,
      }),
      searchHolding: null,
      rules: {
        name: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
        inn: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
        email: [
          { type: 'email', message: 'Неверный формат', trigger: 'blur' },
        ],
        phone: [
          { validator: this.checkPhone, message: 'Неверный формат', trigger: 'blur' },
        ],
      },

    };
  },
  methods: {
    selectInn(value) {
      if (value) {
        this.form.name = value.value;
        this.form.inn = value.data.inn;
        this.form.kpp = value.data.kpp;
        this.form.ogrn = value.data.ogrn;
        this.form.type_1c = value.data.type === 'LEGAL' ? 'Юридическое лицо' : 'Индивидуальный предприниматель';
        if (value.data.address) {
          this.form.u_address = this.form.f_address = value.data.address.unrestricted_value;
        }
        if (value.data.phones) {
          this.form.phone = value.data.phones[0];
        }

        if (value.data.emails) {
          this.form.email = value.data.emails[0];
        }
      }
    },

    backClick() {
      // eslint-disable-next-line no-restricted-globals
      history.go(-1);
    },
    checkPhone(rule, value, callback) {
      if (!value) {
        callback();
      } else {
        value = value.replace(/\D+/gm, '');
        if (value.length !== 11) {
          callback(new Error('Неверный формат'));
        } else {
          callback();
        }
      }
    },
    getHoldings(query) {
      if (query !== '') {
        this.loadingHoldings = true;

        axios.get(`${route('api.user.holdings')}?filter[title]=${query}`).then((response) => {
          this.holdings = response.data.data;
        }).catch((error) => {
          this.$notify.error({
            title: 'Ошибка при получение холдингов',
            message: error,
          });
          this.holdings = [];
        }).finally(() => {
          this.loadingHoldings = false;
        });
      } else {
        this.holdings = [];
      }
    },
    validate() {
      return this.$refs.company_edit_form.validate();
    },
    submit() {
      this.validate().then(() => {
        this.sendRequest();
      }).catch((error) => {
        console.log(error);
        this.$notify.error({
          title: 'Ошибки в форме',
          message: 'Заполните необходимые поля',
        });
      });
    },
    sendRequest() {
      this.loading = true;

      if (!this.form.type) {
        this.form.type_1c = 'Физическое лицо';
      } else if (!this.form.type_1c) {
        this.form.type_1c = 'Юридическое лицо';
      }

      this.form.post(route('users.company.store'), {
        preserveScroll: true,
        onSuccess: () => {
          this.$notify.success({
            title: 'Успешно',
            message: 'Контрагент успешно создан',
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
