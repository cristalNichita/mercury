<template>
  <el-table
    :data="items"
    class="w-100"
    @row-click="openItem"
  >
    <el-table-column
      label="Изображение"
      width="160"
    >
      <template #default="scope">
        <el-image
          v-if="scope.row.image"
          :src="scope.row.image.small"
          fit="cover"
          style="width: 160px; height: 90px"
        />
      </template>
    </el-table-column>

    <el-table-column
      label="Заголовок"
    >
      <template #default="scope">
        {{ scope.row.title }}
      </template>
    </el-table-column>

    <el-table-column
      v-if="pageType === 'news'"
      label="Дата публикации"
      width="160"
    >
      <template #default="scope">
        {{ scope.row.published_at }}
      </template>
    </el-table-column>

    <el-table-column
      label="Активен"
      width="100"
      align="center"
    >
      <template #default="scope">
        <el-checkbox
          :model-value="!!scope.row.active"
          :disabled="isToggling"
          @click.stop="() => toggleActive(scope.row.id)"
        />
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'PagesTable',
  props: {
    items: Array,
    pageType: String,
    pageTypes: Object,
  },
  data() {
    return ({
      isToggling: false,
    });
  },

  methods: {
    openItem(row) {
      this.$inertia.visit(route(`site.${this.pageType}.edit`, row.id));
    },

    toggleActive(id) {
      if (this.isToggling) {
        return;
      }
      console.log(id);

      this.isToggling = true;
      this.$inertia.get(route('site.pages.toggle-active', id), {}, {
        onFinish: () => {
          this.isToggling = false;
        },
        preserveState: true,
        preserveScroll: true,
      });
    },
  },
};
</script>

<style scoped>

</style>
