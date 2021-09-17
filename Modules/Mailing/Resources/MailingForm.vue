<template>
  <div>
    <el-form
      ref="form"
      v-loading="form.processing"
      class="w-50"
      :model="form"
      label-position="top"
      :rules="rules"
    >
      <el-form-item
        v-if="form.id"
        label="id"
      >
        <el-input
          id="id"
          v-model="form.id"
          :disabled="true"
        />
      </el-form-item>
      <el-form-item
        label="Название"
        prop="name"
      >
        <el-input
          id="name"
          v-model="form.name"
          :disabled="disabled"
        />
      </el-form-item>

      <el-form-item
        label="Событие"
        prop="events_id"
      >
        <el-select
          id="event_id"
          v-model="form.event_id"
          placeholder="Выберите событие"
          @change="changeEvent"
        >
          <el-option
            v-for="item in events_list"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item
        v-if="isStasuses"
        label="Статус события"
        prop="events_id"
      >
        <el-select
          id="status_id"
          v-model="form.status_id"
          placeholder="Выберите статус"
        >
          <el-option
            v-for="item in statuses"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>

      <el-form-item
        label="Шаблон письма"
        prop="mail_template"
      >
        <ui-editor
          id="mail_template"
          v-model="form.mail_template"
        />
      </el-form-item>
      <el-form-item
        label="Уведомление получит"
        prop="type"
      >
        <el-switch
          v-model="form.type"
          active-text="Администратор"
          inactive-text="Пользователь"
        />
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import UiEditor from '@/components/UI/UiEditor';

export default {
  name: 'MailingForm',
  components: { UiEditor },
  props: {
    mailing: Object,
    events: Object,
  },
  data() {
    return ({
      form: {
        id: null,
        name: '',
        mail_template: '',
        event_id: '',
        status_id: '',
        type: true,

      },

      events_list: this.events,
      statuses: [],

      rules: {
        name: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
        mail_template: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
      },
    });
  },

  mounted() {
    this.statuses = !this.isNew ? this.mailing.event.statuses : [];
  },

  computed: {
    isNew() {
      return !this.form.id;
    },

    isStasuses() {
      return !!this.statuses.length;
    },
  },

  methods: {
    changeEvent(index) {
      this.statuses = this.events_list[index - 1].statuses;
    },
    validate() {
      Object.keys(this.form).forEach((key) => {
        if (typeof this.form[key] === 'string') {
          this.form[key] = this.form[key].trim();
        }
      });

      return this.$refs.form.validate();
    },

    initForm() {
      const mailingData = this.mailing;

      this.form = {
        ...this.form,
        id: mailingData.id,
        name: mailingData.name,
        event_id: mailingData.event_id,
        status_id: mailingData.status_id,
        mail_template: mailingData.mail_template,
        type: mailingData.type,
      };
    },

    buildFormData() {
      const data = new FormData();

      data.append('name', this.form.name);
      data.append('event_id', this.form.event_id);
      data.append('status_id', this.form.status_id);
      data.append('mail_template', this.form.mail_template);
      data.append('type', this.form.type ? '1' : '0');

      return data;
    },

    sendRequest() {
      const formData = this.buildFormData();
      let url;
      let message;

      if (this.isNew) {
        url = route('mailing.store');
        message = 'Данные добавлены!';
      } else {
        formData.append('_method', 'PUT');

        url = route('mailing.update', this.form.id);
        message = 'Данные обновлены!';
      }

      this.$inertia.post(url, formData, {
        onSuccess: () => {
          this.$notify.success({
            title: 'Успешно',
            message,
          });
        },
        onError: (error) => {
          console.log(error);
        },
        preserveState: this.isNew,
      });
    },

    submit() {
      this.validate().then(() => {
        this.sendRequest();
      }).catch((error) => {
        console.log(error);
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
