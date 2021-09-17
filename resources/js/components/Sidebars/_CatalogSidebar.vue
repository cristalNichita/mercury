<template>
  <el-aside style="width:100%; background-color: rgb(238, 241, 246)">
    <el-menu>
      <el-submenu index="1">
        <template #title>
          <i class="el-icon-message" />Категории
        </template>

        <el-menu-item-group>
          <el-tree
            v-if="categories"
            :data="categories"
            :props="defaultProps"
            node-key="id"
            @node-click="handleNodeClick"
          />
        </el-menu-item-group>
      </el-submenu>
      <el-menu-item
        index="2"
        @click="clickFilter('is_new', true)"
      >
        Новинки
      </el-menu-item>
    </el-menu>
  </el-aside>
</template>

<script>
// @depricated
export default {
  name: 'CatalogSidebarOld',
  data: () => ({
    defaultProps: {
      children: 'children',
      label: 'title',
    },
  }),
  computed: {
    categories() {
      return this.$store.getters['categories/categories'];
    },
  },
  mounted() {
    this.$store.dispatch('categories/getCategories');
  },
  methods: {
    handleNodeClick(category) {
      this.$emit('click-category', category);
    },
    clickFilter(key, param) {
      const params = {};
      params[key] = param;
      this.$emit('click-filter', params);
    },
  },
};
</script>

<style scoped>
.el-header {
    background-color: #B3C0D1;
    color: #333;
    line-height: 60px;
}

.el-aside {
    color: #333;
}
</style>
