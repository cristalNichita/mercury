<template>
  <div class="bg-white shadow-sm p-3 mb-5">
    <div class="h3">
      Основные настройки
    </div>
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
          label="Email"
          prop="email"
        >
          <el-input
            id="email"
            v-model="form.email"
            autofocus
            type="email"
            @keyup.enter.native="submit"
          />
        </el-form-item>

        <el-form-item
          label="Телефон"
          prop="phone"
        >
          <el-input
            id="phone"
            v-model="form.phone"
            @keyup.enter.native="submit"
          />
        </el-form-item>

        <el-form-item
          label="ВКонтакте"
          prop="vk"
        >
          <el-input
            id="vk"
            v-model="form.vk"
            @keyup.enter.native="submit"
          />
        </el-form-item>

        <el-form-item
          label="Facebook"
          prop="facebook"
        >
          <el-input
            id="facebook"
            v-model="form.facebook"
            @keyup.enter.native="submit"
          />
        </el-form-item>

        <el-form-item
          label="Instagram"
          prop="instagram"
        >
          <el-input
            id="instagram"
            v-model="form.instagram"
            @keyup.enter.native="submit"
          />
        </el-form-item>

        <!--        <el-form-item-->
        <!--          label="Пример HTML"-->
        <!--          prop="example_html"-->
        <!--        >-->
        <!--          <v-ace-editor-->
        <!--            v-model:value="form.example_html"-->
        <!--            lang="html"-->
        <!--            theme="chrome"-->
        <!--            style="height: 200px"-->
        <!--          />-->
        <!--        </el-form-item>-->

        <!--        <ui-editor v-model="form.example_html" />-->

        <hr>

        <el-form-item
          label="TinyMce ключ"
          prop="tinymce_key"
        >
          <el-input
            id="tinymce_key"
            v-model="form.tinymce_key"
            @keyup.enter.native="submit"
          />
        </el-form-item>
        <h5 class="mt-5">Настройки СМС (sms.ru)</h5>
        <p class="text-muted">
          Api ключ можно получить в <a
            href="https://sms.ru/?panel=my"
            target="_blank"
          >личном кабинете sms.ru</a> - хранится ключ в файле .env
        </p>
        <el-form-item
          label="Api ключ"
          prop="smsru__api"
        >
          <el-input
            id="smsru__api"
            :model-value="smsruApi"
            :disabled="true"
          />
        </el-form-item>
        <div class="mb-5">
          Баланс:
          <el-tag
            v-loading="smsBalanceLoading"
            :type="smsType"
          >
            <template v-if="smsBalanceLoading">
              Загрузка...
            </template>
            <template v-else>
              <span v-if="!smsError">
                {{ smsBalance.balance }} руб.
              </span>
              <span v-else>
                Ошибка {{ smsBalance.code }}
              </span>
            </template>
          </el-tag>
        </div>
        <h5>Настройки дадата</h5>
        <p class="text-muted">
          Ключ можно получить в <a
            href="https://dadata.ru/profile/"
            target="_blank"
          >личном кабинете dadata.ru</a>
        </p>
        <el-form-item
          label="DADATA токен"
          prop="dadata_token"
        >
          <el-input
            id="dadata_token"
            v-model="form.dadata_token"
            @keyup.enter.native="submit"
          />
        </el-form-item>

        <el-form-item
          label="DADATA секретный ключ"
          prop="dadata_secret"
        >
          <el-input
            id="dadata_secret"
            v-model="form.dadata_secret"
            @keyup.enter.native="submit"
          />
        </el-form-item>

        <div class="mt-5">
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
    smsruApi: { type: String, default: '' },
  },
  data() {
    return {
      form: this.$inertia.form({
        email: this.settings.email,
        phone: this.settings.phone,

        vk: this.settings.vk,
        facebook: this.settings.facebook,
        instagram: this.settings.instagram,

        tinymce_key: this.settings.tinymce_key,
        dadata_token: this.settings.dadata_token,
        dadata_secret: this.settings.dadata_secret,
      }),
      smsBalanceLoading: true,
      smsBalance: false,
    };
  },
  computed: {
    smsError() {
      if (this.smsBalanceLoading) {
        return false;
      }

      return this.smsBalance.code !== '100';
    },
    smsType() {
      if (this.smsBalanceLoading) {
        return 'info';
      }
      if (this.smsError) {
        return 'danger';
      }
      return this.smsBalance < 100 ? 'warning' : 'success';
    },
  },
  mounted() {
    console.log('mounted', route('settings.sms-balance'));
    axios.get(route('settings.sms-balance')).then((response) => {
      this.smsBalance = response.data;
    }).finally(() => {
      this.smsBalanceLoading = false;
    });
  },
  methods: {
    submit() {
      this.form.put(route('settings.settings.save'));
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
