<template>
  <div class="bg-white shadow-sm p-3 mb-5">
    <div class="h3">
      Настройка корзины
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
          label="Сколько хранить брошенные корзины (часы)"
          prop="time_clear"
        >
          <el-input-number
            id="time_clear"
            v-model="form.time_clear"
            autofocus
            @keyup.enter.native="submit"
          />
        </el-form-item>

        <el-form-item
          label="Уведомлять о брошенной корзине через (часы)"
          prop="time_notify"
        >
          <el-input-number
            id="time_notify"
            v-model="form.time_notify"
            @keyup.enter.native="submit"
          />
        </el-form-item>

        <el-form-item
          label="Уведомлять менеджера"
          prop="manager_id_notify"
        >
          <el-select
            id="manager_id_notify"
            v-model="form.manager_id_notify"
            placeholder="Выберите менеджера"
          >
            <el-option
              v-for="item in user_managers"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
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
  name: 'CartSettings',
  layout: (h, page) => h(SettingsLayout, [page]),
  props: {
    settings: { type: Array, required: true },
    managers: { type: Object, required: true },
  },
  data() {
    return {
      form: this.$inertia.form({
        time_clear: parseInt(this.settings.cart__time_clear, 10) ?? 24,
        time_notify: parseInt(this.settings.cart__time_notify, 10) ?? 3,
        manager_id_notify: parseInt(this.settings.cart__manager_id_notify, 10) ?? '',
      }),
      user_managers: this.managers,
    };
  },

  methods: {
    submit() {
      this.form.put(route('carts.settings.save'));
    },
  },
};
</script>

<style lang="scss" scoped>

</style>
