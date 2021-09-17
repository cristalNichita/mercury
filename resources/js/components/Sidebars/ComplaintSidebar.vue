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
          :index="link.route+'.'+link.status"
          @click="menuClick(link.route, link.status)"
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
            @click="menuClick(link.route, link.status)"
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
  name: 'ComplaintSidebar',
  data() {
    return {
      links: [
        {
          title: 'Рассмотрение', route: 'complaints', status: 'in-job', icon: 'el-icon-warning',
        },
        {
          title: 'Отклонена', route: 'complaints', status: 'reject', icon: 'el-icon-warning',
        },
        {
          title: 'Принята', route: 'complaints', status: 'accept', icon: 'el-icon-warning',
        },
        {
          title: 'Исполнено', route: 'complaints', status: 'done', icon: 'el-icon-warning',
        },
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
    menuClick(item, status) {
      this.$inertia.visit(this.route(item, status));
    },
  },
};
</script>

<style scoped>

</style>
