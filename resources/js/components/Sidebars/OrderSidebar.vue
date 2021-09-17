<template>
  <div class="shadow-sm rounded">
    {{ route.current }}
    <el-menu :default-active="submenuRoute">
      <template
        v-for="link in links"
        :key="link.route"
      >
        <el-menu-item
          v-if="!link.childs"
          :key="link.route"
          default-active="2"
          :index="link.route+'.'+link.page"
          @click="menuClick(link.route, link.page)"
        >
          <i
            v-if="link.icon"
            :class="link.icon"
          />
          {{ link.title }}
        </el-menu-item>
        <el-submenu
          v-if="link.childs"
          :index="link.route"
        >
          <template #title>
            <i :class="link.icon" />
            <span>{{ link.title }}</span>
          </template>
          <el-menu-item
            v-for="sublink in link.childs"
            :key="sublink.route"
            :page="link.page"
            :index="sublink.route"
            @click="menuClick(link.route, link.page)"
          >
            <i
              v-if="sublink.icon"
              :class="sublink.icon"
            />
            {{ sublink.title }}
          </el-menu-item>
        </el-submenu>
      </template>
    </el-menu>
  </div>
</template>

<script>
export default {
  name: 'CatalogSidebar',
  data() {
    return {
      links: [
        {
          title: 'Новые', route: 'orders.type', page: 'new', icon: 'el-icon-user',
        },
        {
          title: 'В работе', route: 'orders.type', page: 'work', icon: 'el-icon-user',
        },
        {
          title: 'Отгружённые', route: 'orders.type', page: 'shipped', icon: 'el-icon-user',
        },
        {
          title: 'Завершённые', route: 'orders.type', page: 'сompleted', icon: 'el-icon-user',
        },
        {
          title: 'Доставка', route: 'deliveries', page: 'сompleted', icon: 'el-icon-user',
        }
      ],
    };
  },
  computed: {
    submenuRoute() {
      const [module, action] = this.route().current().split('.');
      const current = `${module}.${action}`;

      return current;
    },
  },
  methods: {
    menuClick(item, page) {
      this.$inertia.visit(this.route(item, page));
    },
  },
};
</script>

<style scoped>

</style>
