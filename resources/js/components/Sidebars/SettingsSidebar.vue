<template>
  <div class="shadow-sm rounded">
    <el-menu :default-active="submenuRoute">
      <el-menu-item
        v-for="link in allLinks"
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
  name: 'SettingsSidebar',
  data() {
    return {
      links: [
        { title: 'Настройки', route: 'settings.settings', icon: 'el-icon-setting' },
        { title: 'Справочники', route: 'settings.directory', icon: 'el-icon-document-copy' },
      ],
    };
  },
  computed: {
    submenuRoute() {
      const [module, action] = this.route().current().split('.');
      return `${module}.${action}`;
    },
    allLinks() {
      if (this.$page.props.sidebarLinks) {
        return this.links.concat(this.$page.props.sidebarLinks);
      }
      return this.links;
    },
  },
  methods: {
    menuClick(item) {
      this.$inertia.visit(this.route(item.index));
    },
  },
};
</script>
