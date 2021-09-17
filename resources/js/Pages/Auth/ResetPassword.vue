<template>
  <guest-layout>
    <ui-errors title="Ошибка" />

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

      <el-form-item
        label="Повтор пароля"
        prop="password_confirmation"
      >
        <el-input
          id="password"
          v-model="form.password_confirmation"
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
          Сменить пароль
        </el-button>
      </el-form-item>
    </el-form>
  </guest-layout>
</template>

<script>
import GuestLayout from '@/Layouts/GuestLayout';
import UiErrors from '@/components/UI/UIErrors';

export default {
  components: { UiErrors, GuestLayout },
  props: {
    email: String,
    token: String,
  },

  data() {
    return {
      form: this.$inertia.form({
        token: this.token,
        email: this.email,
        password: '',
        password_confirmation: '',
      }),
      rules: {
        email: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
          { type: 'email', message: 'Неверный формат', trigger: 'blur' },
        ],
        password: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
        password_confirmation: [
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
        this.form.post(this.route('password.update'), {
          onFinish: () => this.form.reset('password', 'password_confirmation'),
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
