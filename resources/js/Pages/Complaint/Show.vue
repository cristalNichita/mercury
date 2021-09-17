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
      <ui-errors title="Ошибка"/>

      <div
        v-if="form.partner"
        class="p-3 mb-2 bg-danger text-white"
      >
        Рекламация от Партнера
      </div>

      <table class="table">
        <tbody>
        <tr>
          <td scope="row">
            № рекламации
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
            Тип рекламации
          </td>
          <td>
            {{ form.type }}
          </td>
        </tr>
        <tr>
          <td scope="row">
            Описание рекламации
          </td>
          <td>
            {{ form.description }}
          </td>
        </tr>
        <tr>
          <td scope="row">
            Обстоятельства, при которых выявлен дефект
          </td>
          <td>
            {{ form.comment }}
          </td>
        </tr>
        <tr>
          <td scope="row">
            Номер заказа
          </td>
          <td>
            <el-tooltip class="item" effect="dark" content="Перейти в заказ" placement="top-start">
              <inertia-link
                :href="route('orders.web.show', form.order_id)"
                :class="`initialism cursor-pointer`"
              >
                {{ form.order_code }}
              </inertia-link>
            </el-tooltip>
          </td>
        </tr>
        <tr>
          <td scope="row">
            Товары заказа
          </td>
          <td>
            <ul>
              <li
                v-for="item in form.items"
                :key="item.product"
              >
                {{ item.product.title }}
              </li>
            </ul>
          </td>
        </tr>
        <tr>
          <td scope="row">
            Прикрепленные документы
          </td>
          <td>
            <ul>
              <li
                v-for="item in form.files"
                :key="item.file"
              >
                <el-tooltip class="item" effect="dark" content="Скачать документ" placement="top-start">
                  <a
                    :href="item.url"
                    class="initialism"
                    @click.prevent="downloadWithAxios(item.url, item.name)"
                    v-text="item.name"
                  />
                </el-tooltip>
              </li>
            </ul>
          </td>
          <td/>
        </tr>
        </tbody>
      </table>

      <el-form
        col="6"
        ref="complaint_update_form"
        status-icon
        :model="form"
        label-position="top"
        :rules="rules"
        @submit.native.prevent="submit"
      >
        <el-form-item
          label="Статус рекламации"
          prop="status_id"
        >
          <el-select
            class="row col-6"
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
      class="bg-white shadow-sm p-3"
    >
      <ui-errors title="Ошибка"/>
    </div>
  </div>
</template>

<script>
import UiErrors from '@/components/UI/UIErrors';
import ComplaintLayout from '@/Layouts/ComplaintLayout';
import axios from 'axios';

export default {
  name: 'ComplaintsForm',
  // eslint-disable-next-line vue/no-unused-components
  components: { ComplaintLayout, UiErrors },
  layout: (h, page) => h(ComplaintLayout, [page]),
  props: {
    complaint: {
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
      rules: {},
    });
  },
  computed: {
    errors() {
      return this.$page.props.errors;
    },
  },
  methods: {
    demote(index) {
      this.form.companies.splice(index, 1);
    },
    buildForm() {
      return this.$inertia.form({
        id: this.complaint.id,
        status_id: this.complaint.status_id,
        description: this.complaint.description,
        type: this.type,
        comment: this.complaint.comment,
        status_array: this.statuses,
        name: this.complaint.user.name,
        lastname: this.complaint.user.lastname,
        middlename: this.complaint.user.middlename,
        email: this.complaint.user.email,
        phone: this.complaint.user.phone,
        partner: !!this.complaint.user.partner,
        order_id: this.complaint.order.id,
        order_code: this.complaint.order.code,
        items: this.complaint.order.items,
        files: this.complaint.files,
      });
    },
    changeState(state) {
      this.state = state;
    },
    backClick() {
      this.$inertia.visit(route('complaints'));
    },
    validate() {
      return this.$refs.complaint_update_form.validate();
    },
    save() {
      this.validate().then(() => {
        this.form.put(route('complaints.update', this.complaint.id), {
          onBefore: () => {
            this.loading = true;
          },
          onSuccess: () => {
            this.loading = false;
            this.$notify.success({
              title: 'Успешно',
              message: 'Статус рекламации изменен',
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
      }).catch((errors) => {
        console.log(errors);
        this.loading = false;
        this.$notify.error({
          title: 'Ошибки в форме',
          message: 'Заполните необходимые поля',
        });
      });
    },
    orderClick(id) {
      this.$inertia.visit(this.route('orders.view', id));
    },
    forceFileDownload(response, name) {
      const url = window.URL.createObjectURL(new Blob([response.data]));
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', name);
      document.body.appendChild(link);
      link.click();
    },
    downloadWithAxios(url, name) {
      axios({
        method: 'get',
        url,
        responseType: 'arraybuffer',
      })
        .then((response) => {
          this.forceFileDownload(response, name);
        })
        .catch(() => console.log('error occured'));
    },
  },
};
</script>

<style scoped>

.cursor-pointer {
  cursor: pointer;
}

</style>
