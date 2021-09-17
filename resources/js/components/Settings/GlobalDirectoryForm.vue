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
        label="Символьный код"
        prop="code"
      >
        <el-input
          id="content"
          v-model="form.code"
          :disabled="disabled"
        />
      </el-form-item>
    </el-form>
    <div
      v-if="!isNew"
      class="el-form-item__label"
    >
      Элементы списка
    </div>
    <global-directory-item-table
      v-if="!isNew"
      :directory-id="directory.id"
      :items="directory.items"
    />
  </div>
</template>

<script>
import GlobalDirectoryItemTable from '@/components/Settings/GlobalDirectoryItemTable';

export default {
  name: 'GlobalDirectoryForm',
  components: { GlobalDirectoryItemTable },
  props: {
    directory: Object,
  },
  data() {
    return ({
      form: {
        id: null,
        name: '',
        code: '',
      },
      rules: {
        name: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
        code: [
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
      const directoryData = this.directory;

      this.form = {
        ...this.form,
        id: directoryData.id,
        name: directoryData.name,
        code: directoryData.code,
      };
    },

    buildFormData() {
      const data = new FormData();

      data.append('title', this.form.title);
      data.append('name', this.form.name);
      data.append('code', this.form.code);

      return data;
    },

    sendRequest() {
      const formData = this.buildFormData();
      let url;
      let message;

      if (this.isNew) {
        url = route('settings.directory.store');
        message = 'Данные добавлены!';
      } else {
        formData.append('_method', 'PUT');

        url = route('settings.directory.update', this.form.id);
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
