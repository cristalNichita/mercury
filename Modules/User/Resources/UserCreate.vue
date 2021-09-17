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
        Создание нового пользователя
      </div>
    </div>
    <div
      v-loading="loading"
      class="bg-white shadow-sm p-3"
    >
      <el-form
        ref="user_create_form"
        v-loading="form.processing"
        :model="form"
        :rules="rules"
        @submit.native.prevent="submit"
      >
        <!-- todo: выводить нормальные названия ролей -->
        <el-form-item
          label="Роль"
          prop="role"
        >
          <el-select
            v-model="form.role"
            placeholder="Выберите роль"
          >
            <el-option
              v-for="(item, index) in roles"
              :key="index"
              :label="index"
              :value="item"
            />
          </el-select>
        </el-form-item>

        <el-form-item
          label="ФИО"
          prop="name"
        >
          <el-input v-model="form.name" />
        </el-form-item>

        <el-form-item
          label="E-Mail"
          prop="email"
        >
          <el-input
            v-model="form.email"
          />
        </el-form-item>

        <el-form-item
          label="Номер телефона"
          prop="phone"
        >
          <el-input
            v-model="form.phone"
            v-maska="'+7 (###) ###-##-##'"
            placeholder="+7 (___) ___-____"
          />
        </el-form-item>

        <el-form-item
          label="Пароль"
          prop="password"
        >
          <el-input
            v-model="form.password"
            type="password"
          />
        </el-form-item>
        <el-form-item
          label="Подтверждение пароль"
          prop="password_confirmation"
        >
          <el-input
            v-model="form.password_confirmation"
            type="password"
          />
        </el-form-item>
        <el-form-item>
          <el-button
            type="primary"
            class="mt-3"
            @click="submit"
          >
            Создать пользователя
          </el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import UserLayout from '@/Layouts/UserLayout';

export default {
  name: 'Settings',
  layout: (h, page) => h(UserLayout, [page]),
  props: {
    user: { type: Object, required: true },
    roles: { type: Array, required: true },
  },
  data() {
    return {
      form: this.$inertia.form({
        role: '',
        name: '',
        email: '',
        phone: '',
        password: '',
        password_confirmation: '',
      }),
      rules: {
        role: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
        name: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
        email: [
          { type: 'email', message: 'Неверный формат', trigger: 'blur' },
        ],
        phone: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
          {
            min: 16,
            message: 'Не верный формат номера телефона',
            trigger: 'blur',
          },
        ],
        password: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
          {
            min: 6,
            message: 'Пароль должен состоять минимум из 6 символов',
            trigger: 'blur',
          },
        ],
        password_confirmation: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
          { validator: this.confirmPasswordRule, trigger: 'blur' },
        ],
      },
    };
  },
  computed: {
    isDisabled() {
      return this.user.role === 1;
    },
  },
  methods: {
    submit() {
      this.validate().then(() => {
        this.form.post(route('users.store'), {
          onBefore: () => {
            this.loading = true;
          },
          onSuccess: () => {
            this.$notify.success({
              title: 'Успешно',
              message: 'Пользователь успешно создан',
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
            this.loading = false;
          },
        });
      }).catch(() => {
        this.$notify.error({
          title: 'Ошибки в форме',
          message: 'Заполните необходимые поля',
        });
      });
    },
    confirmPasswordRule(rule, value, callback) {
      if (value !== this.form.password) {
        callback(new Error('Пароли не совпадают'));
      } else {
        callback();
      }
    },
    backClick() {
      // eslint-disable-next-line no-restricted-globals
      history.go(-1);
    },
    validate() {
      return this.$refs.user_create_form.validate();
    },
  },
};
</script>

<style lang="scss" scoped>

</style>
