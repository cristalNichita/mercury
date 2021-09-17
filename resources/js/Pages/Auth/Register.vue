<template>
  <guest-layout>
    <ui-errors title="Ошибка регистрации" />

    <el-form
      ref="register_form"
      v-loading="form.processing"
      :model="form"
      label-position="top"
      :rules="rules"
      @submit.native.prevent="submit"
    >
      <el-form-item
        label="Имя"
        prop="name"
      >
        <el-input
          id="name"
          v-model="form.name"
          autofocus
          @keyup.enter.native="submit"
        />
      </el-form-item>

      <el-form-item
        label="Email"
        prop="email"
      >
        <el-input
          id="email"
          v-model="form.email"
          type="email"
          autocomplete="username"
          @keyup.enter.native="submit"
        />
      </el-form-item>

      <el-form-item
        label="Пароль"
        prop="password"
        class="mb-5"
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
        <el-button
          class="w-100"
          type="primary"
          @click="submit"
        >
          Зарегистрироваться
        </el-button>
      </el-form-item>
    </el-form>

    <inertia-link :href="route('login')">
      Уже зарегистрированы?
    </inertia-link>
  </guest-layout>
</template>

<script>
import GuestLayout from '@/Layouts/GuestLayout';
import UiErrors from '@/components/UI/UIErrors';

export default {
  components: { UiErrors, GuestLayout },
  data() {
    return {
      form: this.$inertia.form({
        name: '',
        email: localStorage.getItem('login__email'),
        password: '',
        password_confirmation: '',
        terms: false,
      }),
      rules: {
        name: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
        email: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
          { type: 'email', message: 'Неверный формат', trigger: 'blur' },
        ],
        password: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
          { min: 8, message: 'Минимум 8 символов', trigger: 'blur' },
        ],
      },
    };
  },
  methods: {
    validate() {
      return this.$refs.register_form.validate();
    },
    submit() {
      this.validate().then(() => {
        // Сохраняем логин для последующего использования
        localStorage.setItem('login__email', this.form.email);

        this.form
          .transform((data) => ({ ...data, password_confirmation: this.form.password }))
          .post(this.route('register'), {
            onError: () => {
              this.$notify.error({
                title: 'Ошибка регистрации',
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
