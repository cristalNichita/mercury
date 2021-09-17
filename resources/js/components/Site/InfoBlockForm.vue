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
        label="Описание"
        prop="description"
      >
        <ui-editor
          id="description"
          v-model="form.description"
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
          :on-change="handleImageChange"
          :on-remove="handleImageRemove"
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

      <el-form-item
        label="Цвет фона"
        prop="background_color"
      >
        <el-color-picker
          id="background_color"
          v-model="form.background_color"
          :disabled="disabled"
        />
      </el-form-item>

      <el-form-item
        label="Код для отображения"
        prop="slug"
      >
        <el-input
          id="slug"
          v-model="form.slug"
          :disabled="disabled"
        />
        <a
          v-if="block.page !== null"
          :href="pageLink"
        >Редактировать страницу</a>
      </el-form-item>

      <el-form-item>
        На главной
        <el-switch
          id="in_main"
          v-model="form.in_main"
          active-color="#13ce66"
          :disabled="disabled"
        />
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import UiEditor from '@/components/UI/UiEditor';

export default {
  name: 'InfoBlockForm',
  components: { UiEditor },
  props: {
    disabled: Boolean,
    block: Object,
  },
  data() {
    return ({
      form: {
        id: '',
        title: '',
        description: '',
        image: null,
        newImage: null,
        background_color: '',
        slug: '',
        in_main: false,
      },
      slug: this.block.slug,

      rules: {
        title: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
        description: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
      },
    });
  },

  computed: {
    isNew() {
      return !this.block.id;
    },

    pageLink() {
      return this.block.page ? route('site.info.edit', this.block.page.id) : '#';
    },

    imageList() {
      const list = [];

      if (!this.form.image) {
        return list;
      }

      if (this.form.image.id) {
        list.push({
          ...this.form.image,
          url: this.form.image.thumb_2x,
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
      const blockData = this.block;

      this.form = {
        ...this.form,
        id: blockData.id,
        title: blockData.title,
        description: blockData.description,
        image: blockData.image,
        newImage: null,
        background_color: blockData.background_color,
        slug: blockData.slug,
        in_main: !!blockData.in_main,
      };
    },

    handleImageChange(file) {
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
      this.form.newImage = file.raw;
    },

    handleImageRemove(file) {
      if (file.id) {
        this.sendRemoveImageRequest();
      }

      this.form.image = null;
    },

    buildFormData() {
      const data = new FormData();

      data.append('title', this.form.title);
      data.append('description', this.form.description);

      if (this.form.background_color) {
        data.append('background_color', this.form.background_color);
      }

      data.append('slug', this.form.slug);
      data.append('in_main', this.form.in_main ? '1' : '0');

      if (this.form.newImage) {
        data.append('newImage', this.form.newImage);
      }

      return data;
    },

    sendRemoveImageRequest() {
      this.$inertia.get(route('site.info-blocks.remove-image', this.form.id));
    },

    sendRequest() {
      const formData = this.buildFormData();
      let url;
      let message;

      if (this.isNew) {
        url = route('site.info-blocks.store');
        message = 'Новый блок добавлен!';
      } else {
        formData.append('_method', 'PUT');

        url = route('site.info-blocks.update', this.form.id);
        message = 'Данные блока обновлены!';
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
