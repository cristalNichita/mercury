<template>
  <div class="shadow-sm rounded">
    <el-menu :default-active="submenuRoute">
      <el-menu-item
        v-for="link in links"
        :key="link.route"
        :index="link.route"
        @click="menuClick"
      >
        <i
          v-if="link.icon"
          :class="link.icon"
        />
        {{ link.title }}
      </el-menu-item>
    </el-menu>
  </div>
  <div class="shadow-sm rounded mt-2">
    <el-menu :default-active="submenuRoute">
      <el-menu-item
        v-for="link in extend_links"
        :key="link.route"
        :index="route(link.route, link.routeParam)"
        @click="menuExtendClick"
      >
        <i
          v-if="link.icon"
          :class="link.icon"
        />
        {{ link.title }}
      </el-menu-item>
    </el-menu>
  </div>
</template>

<script>
export default {
  name: 'CatalogSidebar',
  data() {
    return {
      links: [
        { title: 'Товары', route: 'catalog.products', icon: 'el-icon-document-copy' },
        { title: 'Товар дня', route: 'catalog.recommended-products', icon: 'el-icon-document-copy' },
        { title: 'Категории', route: 'catalog.categories', icon: 'el-icon-folder-opened' },
        { title: 'Бренды', route: 'catalog.brands', icon: 'el-icon-apple' },
        { title: 'Параметры', route: 'catalog.parameters', icon: 'el-icon-set-up' },
      ],
      extend_links: [
        {
          title: 'Новинки', route: 'catalog.special-products.index', routeParam: 'is_new', icon: 'el-icon-medal',
        },
        {
          title: 'Акции', route: 'catalog.special-products.index', routeParam: 'is_sale', icon: 'el-icon-sold-out',
        },
        {
          title: 'Выгодные предложения', route: 'catalog.special-products.index', routeParam: 'is_offer', icon: 'el-icon-medal-1',
        },
      ],
    };
  },
  computed: {
    submenuRoute() {
      const [module, action] = this.route().current().split('.');
      let current = `${module}.${action}`;

      // Спецыфичные для модуля правила
      if (current === 'catalog.product') {
        current = 'catalog.products';
      }

      return current;
    },
  },
  methods: {
    menuClick(item) {
      this.$inertia.visit(this.route(item.index));
    },
    menuExtendClick(item) {
      this.$inertia.visit(item.index);
    },
  },
};
</script>

<style scoped>

</style>
