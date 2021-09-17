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
</template>

<script>
export default {
  name: 'SiteSidebar',
  data() {
    return {
      links: [
        { title: 'Слайдер на главной', route: 'site.main-sliders', icon: 'el-icon-s-home' },
        { title: 'Слайдер о компании', route: 'site.about-company-sliders', icon: 'el-icon-s-home' },
        { title: 'Инфо-блоки', route: 'site.info-blocks', icon: 'el-icon-document' },
        { title: 'Контакты', route: 'site.contacts', icon: 'el-icon-document' },
        { title: 'Проекты', route: 'site.projects', icon: 'el-icon-folder-opened' },
        { title: 'Услуги', route: 'site.services', icon: 'el-icon-folder-opened' },
        { title: 'Новости', route: 'site.news', icon: 'el-icon-folder-opened' },
      ],
    };
  },
  computed: {
    submenuRoute() {
      const [module, action] = this.route().current().split('.');
      return `${module}.${action}`;
    },
  },
  methods: {
    menuClick(item) {
      this.$inertia.visit(this.route(item.index));
    },
  },
};
</script>
