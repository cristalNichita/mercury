<template>
  <div>
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
      </div>
    </div>

    <div
      v-loading="loading"
      class="bg-white shadow-sm p-3"
    >
      <ui-errors title="Ошибка" />
      <el-form
        ref="user_company_create_form"
        v-loading="form.processing"
        status-icon
        :model="form"
        label-position="top"
        :rules="rules"
        @submit.native.prevent="submit"
      >
        <el-row :gutter="20">
          <el-col :span="8">
            <h6
              class="mb-3"
            >
              Реквизиты организации
            </h6>
            <el-form-item
              prop="name"
              label="Название организации"
            >
              <ui-dadata-company @select="handleSelectCompany" />
            </el-form-item>

            <el-form-item
              v-if="form.inn"
              label="ИНН"
              prop="inn"
            >
              <el-input
                v-model="form.inn"
              />
            </el-form-item>

            <el-form-item
              v-if="form.ogrn"
              label="ОГРН"
              prop="ogrn"
            >
              <el-input
                v-model="form.ogrn"
              />
            </el-form-item>

            <el-form-item
              v-if="form.kpp"
              label="КПП"
              prop="kpp"
            >
              <el-input
                v-model="form.kpp"
              />
            </el-form-item>
          </el-col>
          <el-col
            v-if="form.inn"
            :span="8"
          >
            <h6
              class="mb-3"
            >
              Юридический адрес
            </h6>

            <el-form-item
              v-if="form.city"
              label="Город/Поселение"
              prop="city"
            >
              <el-input
                v-model="form.city"
              />
            </el-form-item>
            <el-form-item
              v-if="form.street"
              label="Улица"
              prop="street"
            >
              <el-input
                v-model="form.street"
              />
            </el-form-item>
            <el-form-item
              v-if="form.postal_code"
              label="Индекс"
              prop="postal_code"
            >
              <el-input
                v-model="form.postal_code"
              />
            </el-form-item>
            <el-row :gutter="20">
              <el-col :span="form.office ? 12 : 24">
                <el-form-item
                  v-if="form.house"
                  label="Дом"
                  prop="house"
                >
                  <el-input
                    v-model="form.house"
                  />
                </el-form-item>
              </el-col>
              <el-col :span="12">
                <el-form-item
                  v-if="form.office"
                  label="Офис"
                  prop="office"
                >
                  <el-input
                    v-model="form.office"
                  />
                </el-form-item>
              </el-col>
            </el-row>
          </el-col>
          <el-col
            v-if="form.inn"
            :span="8"
          >
            <h6
              class="mb-3"
            >
              Реквизиты банка
            </h6>
            <el-form-item
              label="Наименование банка"
              prop="bank_name"
            >
              <el-input
                v-model="form.bank_name"
              />
            </el-form-item>

            <el-form-item
              label="Расчётный счёт"
              prop="checking_account"
            >
              <el-input
                v-model="form.checking_account"
              />
            </el-form-item>

            <el-form-item
              label="Расчётный счёт"
              prop="bank_bik"
            >
              <el-input
                v-model="form.bank_bik"
              />
            </el-form-item>
            <el-form-item
              label="Корпоративный счёт"
              prop="corporate_account"
            >
              <el-input
                v-model="form.corporate_account"
              />
            </el-form-item>
          </el-col>
        </el-row>

        <el-form-item>
          <el-button
            type="primary"
            @click="save"
          >
            Создать
          </el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import UserLayout from '@/Layouts/UserLayout';
import UiErrors from '@/components/UI/UIErrors';
import UiDadataCompany from '@/components/UI/UiDadataCompany';

export default {
  name: 'Users',
  components: { UiDadataCompany, UserLayout, UiErrors },
  layout: (h, page) => h(UserLayout, [page]),
  props: {
    user: Object,
    teams: Array,
  },
  data() {
    return ({
      form: this.buildForm(),
      loading: false,
      // rules: this.rules,
    });
  },
  computed: {
    errors() {
      return this.$page.props.errors;
    },
    rules() {
      return {
        inn: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
        name: [
          { required: true, message: 'Выберите организацию', trigger: 'blur' },
        ],
      };
    },
  },
  methods: {
    validate() {
      return this.$refs.user_company_create_form.validate();
    },
    buildForm() {
      return this.$inertia.form({
        name: '',
        inn: '',
        ogrn: '',
        kpp: '',
        city: '',
        street: '',
        postal_code: '',
        house: '',
        office: '',
        bank_name: '',
        checking_account: '',
        corporate_account: '',
        bank_bik: '',
      });
    },
    handleSelectCompany(item) {
      this.form.name = item.value;
      this.form.inn = item.data.inn;
      this.form.kpp = item.data.kpp;
      this.form.ogrn = item.data.ogrn;
      this.form.city = item.data.address.data.city
        ? `${item.data.address.data.region_with_type}, ${item.data.address.data.city_with_type}`
        : `${item.data.address.data.region_with_type}, ${item.data.address.data.settlement_with_type}`;
      this.form.street = item.data.address.data.street_with_type;
      this.form.postal_code = item.data.address.data.postal_code;
      this.form.house = item.data.address.data.block
        ? `${item.data.address.data.house} ${item.data.address.data.block_type}${item.data.address.data.block}`
        : item.data.address.data.house;
      this.form.office = item.data.address.data.flat;
    },
    backClick() {
      this.$inertia.visit(route('users'));
    },
    save() {
      this.validate().then(() => {
        this.form.post(route('users.company.store'), {
          onBefore: () => {
            this.loading = true;
          },
          onSuccess: () => {
            this.loading = false;
            this.$notify.success({
              title: 'Успешно',
              message: 'Пользователь успешно сохранён',
            });
          },
          onError: () => {
            this.loading = false;
            this.$notify.error({
              title: 'Ошибка',
              message: 'При создании возникла ошибка',
            });
          },
          preserveState: true,
        });
      }).catch((err) => {
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
