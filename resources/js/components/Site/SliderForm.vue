<template>
  <div>
    <el-form
      ref="form"
      v-loading="form.processing"
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
        v-if="!form.type"
        label="Заголовок"
        prop="title"
      >
        <el-input
          id="title"
          v-model="form.title"
          :disabled="disabled"
        />
      </el-form-item>

      <el-form-item
        v-if="!form.type"
        label="Подзаголовок"
        prop="description"
      >
        <el-input
          id="description"
          v-model="form.description"
          :disabled="disabled"
        />
      </el-form-item>

      <el-form-item
        v-if="!form.type"
        label="Текст кнопки"
        prop="button_text"
      >
        <el-input
          id="button_text"
          v-model="form.button_text"
          :disabled="disabled"
        />
      </el-form-item>

      <el-form-item
        v-if="!form.type"
        label="Цвет кнопки"
        prop="button_color"
      >
        <el-color-picker
          id="button_color"
          v-model="form.button_color"
          :disabled="disabled"
        />
      </el-form-item>

      <el-form-item
        label="Ссылка для перехода"
        prop="url"
      >
        <el-input
          id="url"
          v-model="form.url"
          :disabled="disabled"
        />
      </el-form-item>

      <el-form-item
        label="Изображение"
        prop="image"
      >
        <el-upload
          ref="upload"
          class="upload-demo"
          action="https://jsonplaceholder.typicode.com/posts/"
          :auto-upload="false"
          :file-list="imageList"
          :disabled="disabled"
          :on-change="handleChange"
          :on-remove="handleRemove"
          list-type="picture"
        >
          <el-button
            v-if="!form.image"
            slot="trigger"
            size="small"
            type="primary"
          >
            Добавить
          </el-button>
        </el-upload>
      </el-form-item>

      <el-form-item>
        Активен
        <el-switch
          id="active"
          v-model="form.active"
          active-color="#13ce66"
          :disabled="disabled"
        />
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
export default {
  name: 'SliderForm',
  props: {
    disabled: Boolean,
    slider: { type: Object },
    sliderType: { type: Number },
  },
  data() {
    return ({
      form: {
        id: null,
        type: this.sliderType,
        title: '',
        description: '',
        button_text: '',
        button_color: '',
        url: '',
        image: null,
        active: false,

        new_image: null,
      },
      rules: {
        title: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
        description: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
        button_text: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
        button_color: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
        url: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
        image: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
      },
    });
  },

  computed: {
    isNew() {
      return !this.slider.id;
    },

    imageList() {
      const list = [];

      if (!this.form.image) {
        return list;
      }

      if (this.form.image.id) {
        list.push({
          ...this.form.image,
          url: this.form.image.thumb,
        });
      } else {
        list.push(this.form.image);
      }

      return list;
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
      const sliderData = this.slider;

      this.form = {
        ...this.form,
        id: sliderData.id,
        type: sliderData.type,
        title: sliderData.title,
        description: sliderData.description,
        button_text: sliderData.button_text,
        button_color: sliderData.button_color,
        image: sliderData.image,
        url: sliderData.url,
        active: !!sliderData.active,
      };
    },

    // для работы с изображением
    handleChange(file) {
      const [type] = file.raw.type.split('/');
      if (!(type === 'image')) {
        this.$notify.error({
          title: 'Ошибка заполнения формы',
          message: 'Вы можете добавить только изображение!',
        });
        this.$refs.upload.clearFiles();

        return;
      }

      this.form.image = file;
      this.form.new_image = file.raw;
    },

    handleRemove() {
      this.form.image = null;
    },

    buildFormData() {
      const data = new FormData();

      data.append('type', this.form.type);

      if (!this.form.type) {
        data.append('title', this.form.title);
        data.append('description', this.form.description);
        data.append('button_text', this.form.button_text);
        data.append('button_color', this.form.button_color);
      }

      data.append('url', this.form.url);
      if (this.form.new_image) {
        data.append('new_image', this.form.new_image);
      }
      data.append('active', this.form.active ? '1' : '0');

      return data;
    },

    sendRequest() {
      const formData = this.buildFormData();
      let url;
      let message;

      // eslint-disable-next-line no-unused-vars
      const [__, action] = this.route().current().split('.');
      if (this.isNew) {
        url = route(`site.${action}.store`);
        message = 'Новый слайдер добавлен!';
      } else {
        formData.append('_method', 'PUT');

        url = route(`site.${action}.update`, this.form.id);
        message = 'Данные слайдера обновлены!';
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
        preserveState: false,
      });
    },

    submit() {
      this.validate().then(() => {
        this.sendRequest();
      }).catch(() => {
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
