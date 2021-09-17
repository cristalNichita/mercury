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
        />
      </el-form-item>

      <el-form-item
        v-if="pageType === 'projects'"
        label="Категория"
        prop="category"
      >
        <el-select
          id="title"
          v-model="form.category"
          placeholder="Выберите категорию"
        >
          <el-option
            v-for="item in categories.items"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </el-form-item>

      <el-form-item
        label="Slug"
        prop="slug"
      >
        <el-input
          id="slug"
          v-model="form.slug"
          :disabled="pageType === 'info'"
          placeholder="Оставьте поле пустым, чтобы сгенерировать автоматически"
        />
      </el-form-item>

      <el-form-item
        label="Текст"
        prop="content"
      >
        <ui-editor
          id="content"
          v-model="form.content"
        />
      </el-form-item>

      <el-form-item
        v-if="pageType === 'news'"
        label="Автор"
        prop="author"
      >
        <el-input
          id="author"
          v-model="form.author"
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
          :file-list="mainImageList"
          :disabled="disabled"
          :on-change="handleMainImageChange"
          :on-remove="handleMainImageRemove"
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

      <el-form-item label="Галерея">
        <el-upload
          ref="uploadGallery"
          class="upload-demo"
          action="https://jsonplaceholder.typicode.com/posts/"
          :auto-upload="false"
          :file-list="galleryList"
          :disabled="disabled"
          :on-change="handelGalleryImageChange"
          :on-remove="handleGalleryImageRemove"
          list-type="picture"
        >
          <el-button
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
import UiEditor from '@/components/UI/UiEditor';

export default {
  name: 'PageForm',
  components: { UiEditor },
  props: {
    user: Object,
    page: Object,
    pageType: String,
    pageTypes: Object,
    categories: Object,
  },
  data() {
    const rules = {
      title: [
        { required: true, message: 'Укажите заголовок', trigger: 'blur' },
      ],
      content: [
        { required: true, message: 'Добавьте контент', trigger: 'blur' },
      ],
    };

    if (this.pageType !== 'info') {
      rules.image = [
        { required: true, message: 'Выберите изображение', trigger: 'blur' },
      ];
    }

    if (this.pageType === 'news') {
      rules.author = [
        { required: true, message: 'Укажите автора', trigger: 'blur' },
      ];
    }

    if (this.pageType === 'projects') {
      rules.category = [
        { required: true, message: 'Выберите категорию', trigger: 'blur' },
      ];
    }

    return ({
      form: {
        id: null,
        title: '',
        category: null,
        slug: '',
        content: '',
        type: '',
        url: '',
        image: null,
        newMainImage: null,
        gallery: [],
        newGalleryImages: {},

        author: '',
      },
      rules,
    });
  },

  computed: {
    isNew() {
      return !this.form.id;
    },

    mainImageList() {
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
      console.log(list);
      return list;
    },

    galleryList() {
      const list = [];
      // eslint-disable-next-line array-callback-return
      Object.keys(this.form.gallery).map((key) => {
        if (this.form.gallery[key].id) {
          list.push({
            ...this.form.gallery[key],
            url: this.form.gallery[key].thumb,
          });
        } else {
          list.push(this.form.gallery[key]);
        }
      });
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
      const pageData = this.page;
      console.log(pageData);
      this.form = {
        ...this.form,
        id: pageData.id,
        title: pageData.title,
        category: pageData.category,
        slug: pageData.slug ?? '',
        content: pageData.content,
        type: pageData.type,
        active: !!pageData.active,
        image: pageData.image,
        newMainImage: null,
        gallery: pageData.gallery,

        author: pageData.author,
      };
    },

    handleMainImageChange(file) {
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
      this.form.newMainImage = file.raw;
    },

    handleMainImageRemove() {
      this.form.image = null;
    },

    handelGalleryImageChange(file) {
      const [type] = file.raw.type.split('/');
      if (!(type === 'image')) {
        this.$notify.error({
          title: 'Ошибка заполнения формы',
          message: 'Вы можете добавить только изображение!',
        });
        this.$refs.uploadGallery.clearFiles();

        return;
      }

      this.form.gallery.push(file);
      this.form.newGalleryImages[file.uid] = file.raw;
      console.log(this.form.newGalleryImages);
    },

    handleGalleryImageRemove(file) {
      if (file.id) {
        const index = this.form.gallery.findIndex((el) => el.id === file.id);
        this.form.gallery.splice(index, 1);

        this.sendRemoveGalleryImageRequest(file.id);
      } else if (file.uid) {
        const index = this.form.gallery.findIndex((el) => el.uid === file.uid);
        this.form.gallery.splice(index, 1);

        delete this.form.newGalleryImages[file.uid];
      }
    },

    buildFormData() {
      const data = new FormData();

      data.append('title', this.form.title);
      data.append('slug', this.form.slug ?? '');
      data.append('description', this.form.description ?? '');
      data.append('content', this.form.content);
      data.append('type', this.form.type);

      if (this.form.newMainImage) {
        data.append('newMainImage', this.form.newMainImage);
      }

      // eslint-disable-next-line no-restricted-syntax,guard-for-in
      for (const imageKey in this.form.newGalleryImages) {
        data.append('newGalleryImages[]', this.form.newGalleryImages[imageKey]);
      }

      if (this.form.type === this.pageTypes.news) {
        data.append('author', this.form.author);
      }

      if (this.form.type === this.pageTypes.projects) {
        data.append('category', this.form.category);
      }

      data.append('active', this.form.active ? '1' : '0');

      return data;
    },

    sendRemoveGalleryImageRequest(fileId) {
      this.$inertia.post(route('site.pages.remove-gallery-image', { page: this.form.id, id: fileId }));
    },

    sendRequest() {
      const formData = this.buildFormData();
      let url;
      let message;

      if (this.isNew) {
        url = route(`site.${this.pageType}.store`);
        message = 'Данные добавлены!';
      } else {
        formData.append('_method', 'PUT');

        url = route(`site.${this.pageType}.update`, this.form.id);
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
