<template>
  <div>
    <el-form
      ref="form"
      v-loading="form.processing"
      :model="form"
      :rules="rules"
      class="w-50"
      label-position="top"
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
        label="Содержание (вёрстка)"
        prop="additions"
      >
        <ui-editor
          v-model="form.additions"
        />
      </el-form-item>
    </el-form>
  </div>
</template>

<script>

import UiEditor from '@/components/UI/UiEditor';

export default {
  name: 'ContactForm',
  components: { UiEditor },
  props: {
    contact: Object,
  },
  data() {
    return ({
      form: {
        id: null,
        name: '',
        additions: '',
      },
      rules: {
        name: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
        additions: [
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
      const contactData = this.contact;

      this.form = {
        ...this.form,
        id: contactData.id,
        name: contactData.name,
        additions: contactData.additions,
      };
    },

    buildFormData() {
      const data = new FormData();

      data.append('name', this.form.name);
      data.append('additions', this.form.additions);

      return data;
    },

    sendRequest() {
      const formData = this.buildFormData();
      let url;
      let message;

      if (this.isNew) {
        url = route('site.contacts.store');
        message = 'Данные добавлены!';
      } else {
        formData.append('_method', 'PUT');

        url = route('site.contacts.update', this.form.id);
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
