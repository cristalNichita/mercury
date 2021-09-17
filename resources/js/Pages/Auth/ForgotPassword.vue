<template>
  <guest-layout>
    <div class="mb-2">
      Забыли пароль? Не проблема.
    </div>
    <div class="text-muted mb-3">
      Просто укажите ваш email адрес и мы вышлем ссылку для восстановления пароля
    </div>

    <ui-errors title="Ошибка восстановления пароля" />

    <ui-success :title="status" />

    <el-form
      ref="forgot_form"
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

      <el-form-item>
        <el-button
          class="w-100"
          type="primary"
          @click="submit"
        >
          Сбросить пароль
        </el-button>
      </el-form-item>
    </el-form>

    <inertia-link :href="route('login')">
      Вспомнил пароль!
    </inertia-link>
  </guest-layout>
</template>

<script>

import GuestLayout from '@/Layouts/GuestLayout';
import UiErrors from '@/components/UI/UIErrors';
import UiSuccess from '@/components/UI/UISuccess';

export default {
  components: { UiSuccess, UiErrors, GuestLayout },
  props: { status: String },
  data() {
    return {
      form: this.$inertia.form({
        email: localStorage.getItem('login__email'),
      }),
      rules: {
        email: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
          { type: 'email', message: 'Неверный формат', trigger: 'blur' },
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
      return this.$refs.forgot_form.validate();
    },
    submit() {
      this.validate().then(() => {
        this.form.post(this.route('password.email'), {
          onError: () => {
            this.$notify.error({
              title: 'Ошибка восстановления пароля',
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
