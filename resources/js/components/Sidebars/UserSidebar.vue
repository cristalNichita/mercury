<template>
  <div class="shadow-sm rounded">
    <el-menu>
      <template
        v-for="(link, index) in links"
        :key="index"
      >
        <el-menu-item
          default-active="2"
          :index="route(link.route, link.parameters)"
          @click="menuClick"
        >
          <i
            v-if="link.icon"
            :class="link.icon"
          />
          {{ link.title }}
        </el-menu-item>
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
          title: 'Администраторы',
          route: 'users',
          parameters: { role: 1 },
          icon: 'el-icon-user',
        },
        {
          title: 'Менеджеры',
          route: 'users',
          parameters: { role: 2 },
          icon: 'el-icon-user',
        },
        {
          title: 'Контент менеджеры',
          route: 'users',
          parameters: { role: 3 },
          icon: 'el-icon-user',
        },
        {
          title: 'Пользователи',
          route: 'users',
          parameters: { role: 4 },
          icon: 'el-icon-user',
        },
        {
          title: 'Контрагенты',
          icon: 'el-icon-s-cooperation',
          route: 'users.holdings.index',
          parameters: {},
        },
        {
          title: 'Заявки на документы',
          icon: 'el-icon-s-cooperation',
          route: 'users.documents',
          parameters: {},
        },
      ],
    };
  },
  methods: {
    menuClick(item) {
      this.$inertia.visit(item.index);
    },
  },
};
</script>

<style scoped>

</style>
