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
        Пользователь {{ editableUser.name }}
      </div>
    </div>
    <div class="bg-white shadow-sm p-3">
      <el-form
        ref="user_edit_form"
        v-loading="form.processing"
        :model="form"
        :rules="rules"
        :disabled="editableUser.role === 1"
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
            placeholder="example@mail.ru"
          />
        </el-form-item>

        <el-form-item
          label="Номер телефона"
          prop="phone"
        >
          <el-input
            v-model="form.phone"
            v-maska="'+7 (###) ###-##-##'"
            placeholder="+7 (___) ___-__-__"
          />
        </el-form-item>

        <el-form-item
          label="Новый пароль"
          prop="password"
        >
          <el-input
            v-model="form.password"
            show-password
          />
        </el-form-item>
        <el-form-item>
          <el-button
            type="primary"
            @click="submit"
          >
            Сохранить
          </el-button>
          <el-popconfirm
            confirm-button-text="Да"
            cancel-button-text="Нет"
            icon="el-icon-info"
            icon-color="red"
            title="Вы действительно хотите удалить пользователя?"
            @confirm="deleteUser"
          >
            <template #reference>
              <el-button type="danger">
                Удалить
              </el-button>
            </template>
          </el-popconfirm>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>

import UserLayout from '@/Layouts/UserLayout';
import { mask } from 'maska';

export default {
  name: 'Settings',
  layout: (h, page) => h(UserLayout, [page]),
  props: {
    editableUser: { type: Object, required: true },
    roles: { type: Object, required: true },
  },
  data() {
    return {
      form: this.$inertia.form({
        role: this.editableUser.role,
        name: this.editableUser.name,
        email: this.editableUser.email,
        phone: this.maskPhone(this.editableUser.phone),
        password: '',
      }),
      rules: {
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
          {
            min: 6,
            message: 'Пароль должен быть как минимум 6 символов',
            trigger: 'blur',
          },
        ],
      },
    };
  },
  methods: {
    deleteUser() {
      this.form.delete(route('users.delete', this.editableUser.id), {
        onBefore: () => {
          this.loading = true;
        },
        onSuccess: () => {
          this.$notify.success({
            title: 'Успешно',
            message: 'Пользователь успешно удалён',
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
        preserveState: false,
      });
    },
    submit() {
      this.validate().then(() => {
        this.form.put(route('users.update', this.editableUser.id), {
          onBefore: () => {
            this.loading = true;
          },
          onSuccess: () => {
            this.$notify.success({
              title: 'Успешно',
              message: 'Данные пользователя успешно обновлены',
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
          preserveState: true,
        });
      }).catch(() => {
        this.loading = false;
        this.$notify.error({
          title: 'Ошибки в форме',
          message: 'Заполните необходимые поля',
        });
      });
    },
    backClick() {
      // eslint-disable-next-line no-restricted-globals
      history.go(-1);
    },
    validate() {
      return this.$refs.user_edit_form.validate();
    },
    maskPhone(phone) {
      return phone ? mask(phone, '+7 (###) ###-##-##') : '';
    },
  },
};
</script>

<style lang="scss" scoped>

</style>
