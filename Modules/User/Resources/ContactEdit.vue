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
          <template v-if="contact.id">
            Редактирвоание контакного лица ID {{ contact.id }}
          </template>
          <template v-else>
            Новое контактное лицо
          </template>
        </div>
      </div>
    </div>

    <div
      v-loading="loading"
      class="bg-white shadow-sm p-3"
    >
      <el-form
        ref="contact_edit_form"
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
          label="Имя"
          prop="name"
        >
          <el-input
            id="name"
            v-model="form.name"
          />
        </el-form-item>
        <el-form-item
          label="Должность"
          prop="position"
        >
          <el-input
            id="position"
            v-model="form.position"
          />
        </el-form-item>

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
          label="Адрес"
          prop="address"
        >
          <el-input
            v-model="form.address"
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

      <pre>{{ form }}</pre>
    </div>
  </user-layout>
</template>

<script>
import UserLayout from '@/Layouts/UserLayout';

export default {
  name: 'ContactEdit',
  components: { UserLayout },
  props: {
    contact: {
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
        holding_id: this.contact.holding_id,
        name: this.contact.name,
        position: this.contact.position,
        phone: this.contact.phone,
        email: this.contact.email,
        address: this.contact.address,
      }),
      searchHolding: null,
      rules: {
        name: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
        position: [
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
      return this.$refs.contact_edit_form.validate();
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
      this.form.post(route('users.contacts.store'), {
        preserveScroll: true,
        onSuccess: () => {
          this.$notify.success({
            title: 'Успешно',
            message: 'Контакт успешно создан',
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
