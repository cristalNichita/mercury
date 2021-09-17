require('./bootstrap');

// Import modules...
import { createApp, h } from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import store from '@/store';
import ElementPlus from 'element-plus';
import locale from 'element-plus/lib/locale/lang/ru';
import moment from 'moment';
import Maska from 'maska';

const el = document.getElementById('app');
const app = createApp({
  render: () => h(InertiaApp, {
    initialPage: JSON.parse(el.dataset.page),
    resolveComponent(name) {
      if (name.startsWith('@')) {
        const page = name.split('/');
        const module = page.shift().substr(1);
        // vue нужно добавить чтобы не пытался подгрузить php и другие файлы
        // eslint-disable-next-line global-require,import/no-dynamic-require
        return require(`@modules/${module}/Resources/${page.join('/')}.vue`).default;
      }
      // eslint-disable-next-line global-require,import/no-dynamic-require
      return require(`./Pages/${name}`).default;
    },
  }),
});

app.mixin({ methods: { route } })
  .use(InertiaPlugin)
  .use(ElementPlus, { locale })
  .use(store)
  .use(Maska)
  .mount(el);

// TODO: Убрать devtools на проде
app.config.devtools = true;
// Фильтры в vue 3 ((
app.config.globalProperties.$filters = {
  moneyFormat(str) {
    return (+str) ? (+str).toFixed(0).replace(/\d(?=(\d{3})+$)/g, '$& ') : str;
  },
  timeFormat(str) {
    return str ? moment(String(str)).format('DD.MM.YYYY hh:mm') : str;
  },
  clearPhone(str) {
    return str.replace(/\D+/g, '');
  },
};

InertiaProgress.init({ color: '#FF5722' });
