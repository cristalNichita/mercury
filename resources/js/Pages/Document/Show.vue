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
      </div>
    </div>
    <div
      v-loading="loading"
      class="bg-white shadow-sm p-3"
    >
      <ui-errors title="Ошибка" />

      <div
        v-if="form.partner"
        class="p-3 mb-2 bg-danger text-white"
      >
        Заявка от Партнера
      </div>

      <table class="table table-dark">
        <tbody>
          <tr>
            <td scope="row">
              № заявки
            </td>
            <td>
              {{ form.id }}
            </td>
          </tr>
          <tr>
            <td scope="row">
              Фамилия
            </td>
            <td>
              {{ form.lastname }}
            </td>
          </tr>
          <tr>
            <td scope="row">
              Имя
            </td>
            <td>
              {{ form.name }}
            </td>
          </tr>
          <tr>
            <td scope="row">
              Отчество
            </td>
            <td>
              {{ form.middlename }}
            </td>
          </tr>
          <tr>
            <td scope="row">
              email
            </td>
            <td>
              {{ form.email }}
            </td>
          </tr>
          <tr>
            <td scope="row">
              Тип заявки
            </td>
            <td>
              {{ form.type }}
            </td>
          </tr>
        </tbody>
      </table>

      <el-form
        ref="document_update_form"
        v-loading="form.processing"
        status-icon
        :model="form"
        label-position="top"
        :rules="rules"
        @submit.native.prevent="submit"
      >
        <el-form-item
          label="Статус заявки"
          prop="status_id"
        >
          <el-select
            v-model="form.status_id"
            placeholder="Выберите статус"
            @keyup.enter.native="submit"
          >
            <el-option
              v-for="item in statuses"
              :key="item.name"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>

        <el-form-item>
          <el-button
            type="primary"
            @click="save"
          >
            Сохранить
          </el-button>
        </el-form-item>
      </el-form>
    </div>
    <div
      v-loading="loading"
      class="bg-white shadow-sm p-3"
    >
      <ui-errors title="Ошибка" />
    </div>
  </div>
</template>

<script>
import UiErrors from '@/components/UI/UIErrors';
import UserLayout from '@/Layouts/UserLayout';

export default {
  name: 'DocumentsForm',
  // eslint-disable-next-line vue/no-unused-components
  components: { UserLayout, UiErrors },
  layout: (h, page) => h(UserLayout, [page]),
  props: {
    document: {
      type: Object,
      required: true,
    },
    statuses: {
      type: Array,
      required: true,
    },
    type: {
      type: String,
      required: true,
    },
  },
  data() {
    return ({
      form: this.buildForm(),
      loading: false,
      rules: {
      },
    });
  },
  computed: {
    errors() {
      return this.$page.props.errors;
    },
  },
  methods: {
    buildForm() {
      return this.$inertia.form({
        id: this.document.id,
        status_id: this.document.status_id,
        type: this.type,
        status_array: this.statuses,
        name: this.document.user.name,
        lastname: this.document.user.lastname,
        middlename: this.document.user.middlename,
        email: this.document.user.email,
        phone: this.document.user.phone,
        partner: !!this.document.user.partner,
      });
    },
    changeState(state) {
      this.state = state;
    },
    backClick() {
      this.$inertia.visit(route('users.documents'));
    },
    validate() {
      return this.$refs.document_update_form.validate();
    },
    save() {
      this.validate().then(() => {
        this.form.put(route('users.documents.update', this.document.id), {
          onBefore: () => {
            this.loading = true;
          },
          onSuccess: () => {
            this.loading = false;
            this.$notify.success({
              title: 'Успешно',
              message: 'Заявка успешно сохранёна',
            });
          },
          onError: () => {
            this.loading = false;
            this.$notify.error({
              title: 'Ошибка',
              message: 'При сохранении произошла ошибка',
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
  },
};
</script>

<style scoped>

</style>
