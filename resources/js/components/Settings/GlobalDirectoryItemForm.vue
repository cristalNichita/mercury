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
        label="Дополнительные поля"
        prop="additions"
      >
        <v-jsoneditor
          :value="additions"
          :options="jsonEditorOptions"
        />
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import VJsoneditor from 'v-jsoneditor/src/index';

export default {
  name: 'GlobalDirectoryItemForm',
  components: { VJsoneditor },
  props: {
    directory: Object,
    item: Object,
  },
  data() {
    return ({
      form: {
        id: null,
        name: '',
        additions: {},
      },
      rules: {
        name: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
      },

      jsonEditorOptions: {
        onChangeJSON: this.handleAdditionsChange,
        language: 'ru',
      },
    });
  },

  computed: {
    isNew() {
      return !this.form.id;
    },

    additions() {
      return this.parseJson(this.form.additions);
    },
  },

  methods: {
    parseJson(json) {
      try {
        return JSON.parse(json);
      } catch (e) {
        return json;
      }
    },

    handleAdditionsChange(json) {
      this.form.additions = json;
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
      const itemData = this.item;

      this.form = {
        ...this.form,
        id: itemData.id,
        name: itemData.name,
        additions: this.parseJson(itemData.additions),
      };
    },

    buildFormData() {
      const data = new FormData();

      data.append('name', this.form.name);
      data.append('additions', JSON.stringify(this.form.additions));

      return data;
    },

    sendRequest() {
      const formData = this.buildFormData();
      let url;
      let message;

      if (this.isNew) {
        url = route('settings.directory.item.store', { directory: this.directory.id });
        message = 'Данные добавлены!';
      } else {
        formData.append('_method', 'PUT');

        url = route('settings.directory.item.update', { directory: this.directory.id, item: this.form.id });
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
