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
      <ui-errors title="Ошибка" />
      <el-form
        ref="delivery_update_form"
        v-loading="form.processing"
        status-icon
        :model="form"
        label-position="top"
        :rules="rules"
        @submit.native.prevent="submit"
      >
        <el-row :gutter="20">
          <el-col
            :span="12"
          >
            <h6
              class="mb-3"
            >
              Редактирование службы доставки
            </h6>

            <el-form-item
              v-if="form.name"
              label="Название"
              prop="name"
            >
              <el-input
                v-model="form.name"
              />
            </el-form-item>

            <el-form-item
              v-if="form.code"
              label="Уникальный код"
              prop="code"
            >
              <el-input
                v-model="form.code"
                disabled
              />
            </el-form-item>

            <el-form-item
              prop="active"
            >
              <el-checkbox
                v-model="form.active"
                :true-label="1"
                :false-label="0"
              >
                Активность
              </el-checkbox>
            </el-form-item>
            <el-form-item
              v-if="form.sort"
              label="Сортировка"
              prop="sort"
            >
              <el-input
                v-model="form.sort"
              />
            </el-form-item>
          </el-col>
          <el-col
            :span="12"
            class="d-flex align-items-center justify-content-center"
          >
            <div>
              <h6
                class="mb-3 text-center"
              >
                Изображение
              </h6>

              <el-form-item
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
                    size="large"
                    type="primary"
                  >
                    Добавить
                  </el-button>
                </el-upload>
              </el-form-item>
            </div>
          </el-col>
        </el-row>

        <el-form-item>
          <el-button
            v-if="$page.props.user.role === 1"
            type="primary"
            @click="save"
          >
            Сохранить
          </el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>
<script>
import OrderLayout from '@/Layouts/OrderLayout';

export default {
  name: 'Show',
  layout: (h, page) => h(OrderLayout, [page]),
  props: {
    item: {
      type: Object,
      default: () => ({}),
    },
  },
  data() {
    return ({
      showPassword: false,
      form: this.buildForm(),
      loading: false,
      image: null,
      newImage: null,
      // rules: this.rules,
    });
  },
  computed: {
    errors() {
      return this.$page.props.errors;
    },
    rules() {
      return {
        inn: [
          { required: true, message: 'Обязательное поле', trigger: 'blur' },
        ],
        name: [
          { required: true, message: 'Выберите организацию', trigger: 'blur' },
        ],
      };
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
    backClick() {
      // eslint-disable-next-line no-restricted-globals
      history.go(-1);
    },
    buildForm() {
      return this.$inertia.form({
        name: this.item.name,
        code: this.item.code,
        active: this.item.active,
        sort: this.item.sort,
        newImage: null,
        image: this.item.image,
      });
    },
    validate() {
      return this.$refs.delivery_update_form.validate();
    },
    buildFormData() {
      const data = new FormData();

      Object.keys(this.form).forEach((key) => {
        data.append(key, this.form[key]);
      });

      if (!this.form.newImage) {
        data.delete('newImage');
      }
      data.append('_method', 'PUT');

      return data;
    },
    save() {
      this.validate().then(() => {
        const formData = this.buildFormData();

        this.$inertia.post(route('delivery.update', this.item.id), formData, {
          onBefore: () => {
            this.loading = true;
          },
          onSuccess: () => {
            this.loading = false;
            this.$notify.success({
              title: 'Успешно',
              message: 'Служба доставки успешно обновлена',
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
      }).catch((err) => {
        this.loading = false;
        this.$notify.error({
          title: 'Ошибки в форме',
          message: 'Заполните необходимые поля',
        });
      });
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
      this.form.newImage = file.raw;
    },

    handleRemove() {
      setTimeout(() => {
        this.form.image = null;
      }, 600);
    },
  },
};
</script>

<style scoped>

</style>
