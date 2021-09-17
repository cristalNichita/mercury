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

    </el-form>
  </div>
</template>

<script>

export default {
  name: 'MailingEventStatusForm',
  props: {
    event: Object,
    status: Object,
  },
  data() {
    return ({
      form: {
        id: null,
        name: '',
      },
      rules: {
        name: [
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

    initForm() {
      const statusData = this.status;

      this.form = {
        ...this.form,
        id: statusData.id,
        name: statusData.name,
      };
    },

    buildFormData() {
      const data = new FormData();

      data.append('name', this.form.name);

      return data;
    },

    sendRequest() {
      const formData = this.buildFormData();
      let url;
      let message;

      if (this.isNew) {
        url = route('events.statuses.store', { event: this.event.id });
        message = 'Данные добавлены!';
      } else {
        formData.append('_method', 'PUT');

        url = route('events.statuses.update', { event: this.event.id, status: this.form.id });
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
