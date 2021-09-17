<template>
  <div>
    <div class="row mt-3 mb-3">
      <div class="col-md-6">
        <el-input
          v-model="filterTitle"
          placeholder="Поиск по названию"
        />
      </div>

      <div class="col-md-6 d-flex justify-content-around align-items-center">
        <el-radio
          v-model="filterActive"
          :label="null"
        >
          Все
        </el-radio>
        <el-radio
          v-model="filterActive"
          label="0"
        >
          Неактивные
        </el-radio>
        <el-radio
          v-model="filterActive"
          label="1"
        >
          Активные
        </el-radio>
      </div>
    </div>

    <el-tree
      ref="tree"
      class="filter-tree"
      :data="categories"
      default-expand-all
      :filter-node-method="filterNode"
      node-key="id"
    >
      <template #default="{ data }">
        <span
          class="custom-tree-node"
          :class="{'b-category__inactive': !data.active}"
        >
          <span class="mr-2">{{ data.title }}</span>
          <span>({{ data.product_count }})</span>
          <span>
            <el-button
              v-if="!data.image&&!data.children.length"
              type="text"
              class="ml-2"
              @click="showUploadModal(data)"
            >
              Добавить картинку
            </el-button>
            <span
              v-else-if="data.image"
              class="ml-2"
            >
              <img
                class="b-category__image"
                :src="data.image.small"
                alt=""
                @click="showViewer(data.image)"
              >
              <el-button
                type="text"
                class="ml-2"
                icon="el-icon-close"
                @click="removeCategoryImage(data.id)"
              />
            </span>
          </span>
        </span>
      </template>
    </el-tree>
    <el-dialog
      v-model="categoryImageUploading"
      title="Картинка для категории"
      center
      @close="cancelUpdateImage"
    >
      <el-upload
        ref="upload"
        class="upload-demo text-center"
        action="https://jsonplaceholder.typicode.com/posts/"
        :auto-upload="false"
        :on-change="handleImageChange"
        :on-remove="handleImageRemove"
        list-type="picture"
      >
        <el-button
          v-if="!selectedImage"
          slot="trigger"
          size="small"
          type="primary"
        >
          Добавить
        </el-button>
      </el-upload>
      <template #footer>
        <div>
          <el-button @click="cancelUpdateImage">
            Отмена
          </el-button>
          <el-button
            type="primary"
            @click="updateCategoryImage"
          >
            Окей
          </el-button>
        </div>
      </template>
    </el-dialog>
    <el-image-viewer
      v-if="viewerShowing"
      :hide-on-click-modal="true"
      :url-list="viewerImageList"
      @close="closeViewer"
    />
  </div>
</template>

<script>
import CatalogLayout from '@/Layouts/CatalogLayout';

export default {
  name: 'Categories',
  layout: (h, page) => h(CatalogLayout, [page]),
  props: {
    filter: Object,
    // eslint-disable-next-line no-bitwise,vue/require-prop-type-constructor
    categories: Array | Object,
  },
  data: () => ({
    categoryImageUploading: false,
    selectedCategory: null,
    selectedImage: null,
    viewerShowing: false,
    viewerImageList: [],
    filterTitle: '',
    filterActive: null,
    defaultProps: {
      children: 'children',
      label: 'title',
    },
  }),
  computed: {},
  watch: {
    filterTitle(val) {
      this.$refs.tree.filter({ title: val });
    },
    filterActive(val) {
      this.$refs.tree.filter({ active: val });
    },
  },
  mounted() {
  },
  methods: {
    filterNode(filter, data) {
      let filtered = true;
      // eslint-disable-next-line no-prototype-builtins
      if (filter.hasOwnProperty('active') && filter.active !== null) {
        filtered = data.active === Number(filter.active);
      }
      // eslint-disable-next-line no-prototype-builtins
      if (filter.hasOwnProperty('title') && filter.title !== '') {
        filtered = filtered && (data.title.search(filter.title) !== -1);
      }

      return filtered;
    },
    showUploadModal(data) {
      this.selectedCategory = data;
      this.categoryImageUploading = true;
    },
    handleImageChange(file) {
      const [type] = file.raw.type.split('/');

      if (!(type === 'image')) {
        this.$notify.error({
          title: 'Ошибка',
          message: 'Вы можете добавить только изображение!',
        });
        this.$refs.upload.clearFiles();
        return;
      }
      this.selectedImage = file;
    },
    handleImageRemove() {
      this.selectedImage = null;
    },
    cancelUpdateImage() {
      this.categoryImageUploading = false;
      this.selectedImage = null;
      this.selectedCategory = null;
      this.$refs.upload.clearFiles();
    },
    updateCategoryImage() {
      if (!this.selectedImage) {
        this.$notify.error({
          title: 'Ошибка',
          message: 'Выберите изображение',
        });
        return;
      }
      const data = {
        image: this.selectedImage.raw,
      };
      this.$inertia.post(route('catalog.category.updateImage', this.selectedCategory.id), data, {
        onSuccess: () => {
          this.$notify.success({
            title: 'Успешно',
            message: 'Изображение успешно обновлено',
          });
          this.cancelUpdateImage();
        },
        onError: (errors) => {
          Object.values(errors).forEach((value) => {
            this.$notify.error({
              title: 'Ошибка',
              message: value,
            });
          });
        },
      });
    },
    removeCategoryImage(categoryId) {
      this.$inertia.post(route('catalog.category.removeImages', categoryId), {}, {
        onSuccess: () => {
          this.$notify.success({
            title: 'Успешно',
            message: 'Изображение успешно удалено',
          });
          this.cancelUpdateImage();
        },
        onError: (errors) => {
          Object.values(errors).forEach((value) => {
            this.$notify.error({
              title: 'Ошибка',
              message: value,
            });
          });
        },
      });
    },
    getImageList(category) {
      const list = [];

      if (!category.image) {
        return list;
      }

      if (category.image.id) {
        list.push({
          ...category.image,
          url: category.image.thumb,
        });
      } else {
        list.push(category.image);
      }

      return list;
    },
    showViewer(image) {
      this.viewerImageList = [image.big];
      this.viewerShowing = true;
    },
    closeViewer() {
      this.viewerShowing = false;
    },
  },

};
</script>

<style scoped lang="scss">
.b-category {
  &__inactive {
    text-decoration: line-through;
  }
  &__image {
    width: 25px;
  }
}
</style>
