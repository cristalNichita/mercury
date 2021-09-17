<template>
  <guest-layout>
    <ui-errors title="Ошибка авторизации" />

    <el-form
      ref="login_form"
      v-loading="form.processing"
      :model="form"
      label-position="top"
      :rules="rules"
      @submit.native.prevent="submit"
    >
      <el-form-item
        label="Email"
        prop="email"
      >
        <el-input
          id="email"
          v-model="form.email"
          autofocus
          type="email"
          autocomplete="username"
          @keyup.enter.native="submit"
        />
      </el-form-item>

      <el-form-item
        label="Пароль"
        prop="password"
      >
        <el-input
          id="password"
          v-model="form.password"
          show-password
          autocomplete="current-password"
          @keyup.enter.native="submit"
        />
      </el-form-item>

      <el-form-item>
        <el-checkbox v-model="form.remember">
          Запомнить меня
        </el-checkbox>
      </el-form-item>

      <el-form-item>
        <el-button
          class="w-100"
          type="primary"
          @click="submit"
        >
          Войти
        </el-button>
      </el-form-item>
    </el-form>

    <inertia-link
      v-if="canResetPassword"
      :href="route('password.request')"
    >
      Забыли пароль?
    </inertia-link>
  </guest-layout>
</template>

<script>
import GuestLayout from '@/Layouts/GuestLayout';
import UiErrors from '@/components/UI/UIErrors';

export default {
  components: { UiErrors, GuestLayout },
  props: {
    canResetPassword: Boolean,
    status: String,
  },
  data() {
    return {
      form: this.$inertia.form({
        email: localStorage.getItem('login__email'),
        password: '',
        remember: true,
      }),
      rules: {
        email: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
          { type: 'email', message: 'Неверный формат', trigger: 'blur' },
        ],
        password: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
      },
    };
  },
  computed: {
    errors() {
      return this.$page.props.errors;
    },
  },
  methods: {
    validate() {
      return this.$refs.login_form.validate();
    },
    submit() {
      this.validate().then(() => {
        // Сохраняем логин для последующего использования
        localStorage.setItem('login__email', this.form.remember ? this.form.email : '');

        this.form
          .transform((data) => ({ ...data, remember: this.form.remember ? 'on' : '' }))
          .post(this.route('login'), {
            onError: () => {
              this.$notify.error({
                title: 'Ошибка авторизации',
                message: 'Исправте введенные данные',
              });
            },
          });
      }).catch(() => {
        this.$notify.error({
          title: 'Ошибки в форме',
          message: 'Заполните необходимые поля',
        });
      });
    },
  },
};
</script>
