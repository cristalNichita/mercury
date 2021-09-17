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
          <template v-if="bank_requisite.id">
            Редактирование реквизитов ID {{ bank_requisite.id }}
          </template>
          <template v-else>
            Новый реквизит
          </template>
        </div>
      </div>
    </div>

    <div class="bg-white shadow-sm p-3">
      <el-form
        ref="bank_requisite_edit_form"
        v-loading="form.processing"
        :model="form"
        :rules="rules"
        label-width="120px"
        @submit.native.prevent="submit"
      >
        <el-form-item
          label="Компания"
          prop="company_name"
        >
          <el-input
            id="company_name"
            v-model="form.company_name"
            readonly=true
          />

<!--          <el-input-->
<!--            id="company_id"-->
<!--            v-model="form.company_id"-->
<!--            type="hidden"-->
<!--          />-->
        </el-form-item>

        <hr>

        <el-form-item
          label="Банк"
          prop="name"
        >
          <el-input
            id="name"
            v-model="form.name"
          />
        </el-form-item>

        <el-form-item
          label="БИК"
          prop="bik"
        >
          <el-input
            id="bik"
            v-model="form.bik"
            v-maska="'#########'"
            placeholder="123456789"
          />
        </el-form-item>

        <el-form-item
          label="Корр. счет"
          prop="kor"
        >
          <el-input
            id="kor"
            v-model="form.kor"
            v-maska="'####-####-####-####-####'"
            placeholder="1234-5678-9876-5432-1023"
          />
        </el-form-item>

        <el-form-item
          label="Расч. счет"
          prop="invoice"
        >
          <el-input
            id="invoice"
            v-model="form.invoice"
            v-maska="'####-####-####-####-####'"
            placeholder="1234-5678-9876-5432-1023"
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

export default {
  name: 'BankRequisiteEdit',
  components: { UserLayout },
  props: {
    bank_requisite: {
      type: Object,
      required: true,
    },
    company: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      loading: false,

      form: this.$inertia.form({
        //company_id: this.company.id,
        company_name: this.company.name,
        name: this.bank_requisite.name,
        bik: this.bank_requisite.bik,
        kor: this.bank_requisite.kor,
        invoice: this.bank_requisite.invoice
      }),

      rules: {
        company_id: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' }
        ],
        name: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
        bik: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
        kor: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
        invoice: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
      },

    };
  },
  methods: {
    backClick() {
      // eslint-disable-next-line no-restricted-globals
      history.go(-1);
    },

    validate() {
      return this.$refs.bank_requisite_edit_form.validate();
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

      this.form.post(route('users.company.bank-requisites.store',{
        company: this.company.id
      }), {
        preserveScroll: true,
        onSuccess: () => {
          this.$notify.success({
            title: 'Успешно',
            message: 'Банковский счет создан',
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
