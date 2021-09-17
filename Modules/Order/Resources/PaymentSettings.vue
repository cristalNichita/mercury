<template>
  <div class="bg-white shadow-sm p-3 mb-5">
    <div class="h3">
      Настройка оплаты (Uniteller)
    </div>
    <p class="text-muted">
      Данные настройки можно получить в <a
        href="https://lk.uniteller.ru/#/authparams/"
        target="_blank"
      >личном кабинете uniteller.ru</a>
    </p>
    <div class="row">
      <el-form
        ref="login_form"
        v-loading="form.processing"
        :model="form"
        label-position="top"
        class="col-12 col-lg-6"
        @submit.native.prevent="submit"
      >
        <el-form-item
          label="Login"
          prop="login"
        >
          <el-input
            id="login"
            v-model="form.login"
            autofocus
            @keyup.enter.native="submit"
          />
        </el-form-item>

        <el-form-item
          label="Password"
          prop="password"
        >
          <el-input
            id="password"
            v-model="form.password"
            @keyup.enter.native="submit"
          />
        </el-form-item>

        <el-form-item
          label="Point ID"
          prop="point_id"
        >
          <el-input
            id="point_id"
            v-model="form.point_id"
            @keyup.enter.native="submit"
          />
        </el-form-item>

        <div class="">
          <el-button
            type="success"
            native-type="submit"
            icon="el-icon-edit-outline"
          >
            Сохранить
          </el-button>
        </div>
      </el-form>
    </div>
  </div>
</template>

<script>
import SettingsLayout from '@/Layouts/SettingsLayout';

export default {
  name: 'Settings',
  layout: (h, page) => h(SettingsLayout, [page]),
  props: {
    settings: { type: Array, required: true },
  },
  data() {
    return {
      form: this.$inertia.form({
        login: this.settings.uniteller__login,
        password: this.settings.uniteller__password,
        point_id: this.settings.uniteller__point_id,
      }),
    };
  },
  methods: {
    submit() {
      this.form.put(route('orders.payment-settings.save'));
    },
  },
};
</script>

<style lang="scss" scoped>

</style>
