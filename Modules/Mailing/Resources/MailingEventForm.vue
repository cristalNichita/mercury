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
          id="title"
          v-model="form.name"
          :disabled="disabled"
        />
      </el-form-item>
      <el-form-item
        label="Обработчик события"
        prop="handling"
      >
        <el-select
          id="handling"
          v-model="form.handle_name"
          placeholder="Выберите обработчик"
          @change="changeHandling"
        >
          <el-option
            v-for="(item, idx) in list_handlings"
            :key="idx"
            :label="item"
            :value="item"
          />
        </el-select>
      </el-form-item>
      <hr>
      <el-form-item
        label="Классы оброботчики"
        prop="handling_class_event"
      >
        <el-input
          id="handling_class_event"
          v-model="form.event_class"
          :readonly="true"
        />
      </el-form-item>
      <el-form-item
        label="Классы оброботчики"
        prop="handling_class_listener"
      >
        <el-input
          id="handling_class_listener"
          v-model="form.listener_class"
          :readonly="true"
        />
      </el-form-item>
      <hr>
    </el-form>
    <div
      v-if="!isNew"
      class="el-form-item__label"
    >
      Статусы
    </div>
    <mailing-event-status-table
      v-if="!isNew"
      :event-id="event.id"
      :statuses="event.statuses"
    />
  </div>
</template>

<script>
import MailingEventStatusTable from '@modules/Mailing/Resources/MailingEventStatusTable';

export default {
  name: 'MailingEventForm',
  components: { MailingEventStatusTable },
  props: {
    event: Object,
    handlings: Object,
  },
  data() {
    return ({
      form: {
        id: null,
        name: '',
        handling: {},
        handle_name: '',
        event_class: '',
        listener_class: '',
      },

      list_handlings: Object.keys(this.handlings),

      rules: {
        name: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
        handling: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
      },
    });
  },

  computed: {
    isNew() {
      return !this.form.id;
    },
  },

  methods: {
    validate() {
      Object.keys(this.form).forEach((key) => {
        if (typeof this.form[key] === 'string') {
          this.form[key] = this.form[key].trim();
        }
      });

      return this.$refs.form.validate();
    },

    changeHandling(index) {
      const handling = this.handlings[index];
      console.log(Object.keys(handling));
      if (Object.keys(handling).length) {
        // eslint-disable-next-line prefer-destructuring
        this.form.event_class = Object.keys(handling)[0];
        this.form.listener_class = handling[this.form.event_class].join('\\');
        this.form.handling = {
          event_name: this.form.handle_name,
          handle: handling,
        };
      } else {
        this.form.event_class = '';
        this.form.listener_class = '';
        this.form.handling = {};
      }
    },

    initForm() {
      const eventData = this.event;
      const handling = eventData.handling;
      let event_class = '';
      let listener_class = '';
      let handle_name = '';

      if (handling.length) {
        handle_name = handling.event_name;
        event_class = Object.keys(handling.handle)[0];
        const listener = handling.handle[event_class];
        listener_class = listener.join('\\');
      }

      this.form = {
        ...this.form,
        id: eventData.id,
        name: eventData.name,
        handling: eventData.handling,
        handle_name,
        event_class,
        listener_class,
      };
    },

    buildFormData() {
      const data = new FormData();

      data.append('name', this.form.name);
      data.append('handling', JSON.stringify(this.form.handling));

      return data;
    },

    sendRequest() {
      const formData = this.buildFormData();
      let url;
      let message;

      if (this.isNew) {
        url = route('events.store');
        message = 'Данные добавлены!';
      } else {
        formData.append('_method', 'PUT');

        url = route('events.update', this.form.id);
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
