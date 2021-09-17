<template>
  <div class="b-header shadow-sm bg-white">
    <div class="container-fluid container-xxl">
      <div class="row align-items-center">
        <div class="col">
          <el-menu
            class="b-header__menu"
            mode="horizontal"
            :default-active="topRoute"
          >
            <el-menu-item
              v-for="link in links"
              :key="link.route"
              :index="link.route"
              @click="menuClick"
            >
              {{ link.title }}
            </el-menu-item>
          </el-menu>
        </div>
        <div class="col-auto">
          <inertia-link to="/">
            <img
              class="b-header__logo"
              src="@assets/logo.png"
              alt=""
            >
          </inertia-link>
        </div>
        <div class="col">
          <div class="float-right">
            <el-menu
              class="b-header__menu ml-auto"
              mode="horizontal"
              :default-active="topRoute"
            >
              <template v-for="link in linksRight">
                <el-menu-item
                  v-if="!link.childs"
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
                <el-submenu
                  v-else
                  :key="link.route"
                  :index="link.route"
                >
                  <template #title>
                    <i :class="link.icon" />
                    <span>{{ link.title }}</span>
                  </template>
                  <el-menu-item
                    v-for="sublink in link.childs"
                    :key="sublink.route"
                    :index="sublink.route"
                    @click="menuClick"
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
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// import { computed } from 'vue';
// import { usePage } from '@inertiajs/inertia-vue3';

export default {
  name: 'Header',
  data() {
    return {
      open: false,
    };
  },
  computed: {
    topRoute() {
      return this.route().current().split('.')[0];
    },
    links() {
      const { role } = this.$page.props.user;

      const links = [
        { title: 'Каталог', route: 'catalog' },
      ];

      if (role === 1 || role === 2) {
        links.push({ title: 'Заказы', route: 'orders' });
      }

      if (role === 1 || role === 3) {
        links.push({ title: 'Сайт', route: 'site' });
      }

      if (role === 1) {
        links.push({ title: 'Пользователи', route: 'users' });
        links.push({ title: 'Рекламации', route: 'complaints' });
      }

      return links;
    },
    linksRight() {
      const linksRight = [
        {
          title: this.$page.props.user.name,
          route: 'users.profile',
          icon: 'el-icon-user-solid',
          childs: [
            {
              type: 1,
              title: 'Выйти',
              route: 'logout',
              icon: 'el-icon-user-solid',
            },
          ],
        },
      ];

      if (this.$page.props.user.role === 1) {
        linksRight.push({ title: 'Отчеты', route: 'reports' });
        linksRight.push({ title: 'Настройки', route: 'settings' });
      }

      return linksRight;
    },
  },
  methods: {
    menuClick(item) {
      if (item.index === 'logout') {
        this.$inertia.post(this.route(item.index));
      } else {
        this.$inertia.visit(this.route(item.index));
      }
    },
  },
};
</script>

<style scoped lang="scss">
  .b-header {

    &__menu {
      border-bottom: none;
    }

    &__logo {
      height: 32px;
    }
  }
</style>
