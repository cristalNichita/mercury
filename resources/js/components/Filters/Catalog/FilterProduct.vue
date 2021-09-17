<template>
  <el-form
    ref="form"
    :model="form"
    label-width="auto"
    size="mini"
  >
    <el-form-item label="Поиск по названию">
      <el-input v-model="form.title" />
    </el-form-item>
    <el-form-item label="Поиск по артиклю">
      <el-input v-model="form.article_number" />
    </el-form-item>
    <el-form-item label="Поиск по цене">
      <el-input
        v-model="form.price"
        type="number"
      />
    </el-form-item>
    <el-form-item label="Поиск по скидке">
      <el-input
        v-model="form.discount"
        type="number"
      />
    </el-form-item>
    <el-form-item label="Поиск по описанию">
      <el-input
        v-model="form.description"
        type="textarea"
      />
    </el-form-item>
    <el-form-item label="Категории">
      <el-select
        v-model="form.c"
        multiple
        filterable
        autocomplete
      >
        <el-option
          v-for="item in categories"
          :key="item.id"
          :label="item.title"
          :value="item.id"
        />
      </el-select>
    </el-form-item>

    <el-form-item label="Бренды">
      <el-select
        v-model="form.b"
        multiple
        filterable
        autocomplete
      >
        <el-option
          v-for="item in brands"
          :key="item.id"
          :label="item.title"
          :value="item.id"
        />
      </el-select>
    </el-form-item>

    <el-form-item label="Параметры">
      <el-select
        v-for="param in parameters"
        :key="param.id"
        v-model="form.pv[param.id]"
        multiple
        filterable
      >
        <el-option
          v-for="item in param.values"
          :key="item.id"
          :label="item.title"
          :selected="item.id ==1"
          :value="item.id"
        />
      </el-select>
    </el-form-item>

    <el-form-item label="Новинки">
      <el-radio-group v-model="form.is_new">
        <el-radio :label="null">
          Все
        </el-radio>
        <el-radio label="0">
          Не активные
        </el-radio>
        <el-radio label="1">
          Активные
        </el-radio>
      </el-radio-group>
    </el-form-item>
    <el-form-item label="Акции">
      <el-radio-group v-model="form.is_sale">
        <el-radio :label="null">
          Все
        </el-radio>
        <el-radio label="0">
          Не активные
        </el-radio>
        <el-radio label="1">
          Активные
        </el-radio>
      </el-radio-group>
    </el-form-item>
    <el-form-item label="Выгодное предложение">
      <el-radio-group v-model="form.is_offer">
        <el-radio :label="null">
          Все
        </el-radio>
        <el-radio label="0">
          Не активные
        </el-radio>
        <el-radio label="1">
          Активные
        </el-radio>
      </el-radio-group>
    </el-form-item>
    <div class="demo-input-size">
      <el-form-item label="Рейтинг">
        <el-input
          v-model="form.rating"
          type="number"
        />
      </el-form-item>
      <el-form-item label="Остаток">
        <el-input
          v-model="form.balance"
          type="number"
        />
      </el-form-item>
    </div>

    <el-form-item size="large">
      <el-button
        type="primary"
        @click.prevent="onSubmit"
      >
        Применить
      </el-button>
      <el-button @click.prevent="onCancel">
        Отменить
      </el-button>
    </el-form-item>
  </el-form>
</template>

<script>
export default {
  name: 'FilterProduct',
  props: {
    filter: {
      type: Object,
      default: () => ({}),
    },
  },
  data() {
    return {
      form: {
        title: '',
        description: '',
        article_number: '',
        price: null,
        discount: null,
        c: [],
        b: [],
        pv: {},
        is_new: null,
        is_sale: null,
        is_offer: null,
        rating: null,
        balance: null,

      },
    };
  },
  computed: {
    parameters() {
      return this.$store.getters['parameters/allParameters'];
    },
    categories() {
      return this.$store.getters['categories/allCategories'];
    },
    brands() {
      return this.$store.getters['brands/allBrands'];
    },
  },
  mounted() {
    this.$store.dispatch('parameters/getAllParameters');
    this.$store.dispatch('categories/getAllCategories');
    this.$store.dispatch('brands/getAllBrands');
    this.setFilter();
  },
  methods: {
    onSubmit() {
      const fields = Object.keys(this.form);
      const params = {};

      fields.forEach((key) => {
        const value = this.form[key];
        if (value || Number.isInteger(value)) {
          params[key] = value;
        }
      });

      this.$emit('submit', params);
    },
    onCancel() {
      this.$emit('cancel-form');
    },
    setFilter() {
      Object.keys(this.filter).forEach((key) => {
        let val = this.filter[key] || null;
        if (val) {
          if (['c', 'b'].indexOf(key) > -1) {
            val = val.map((item) => Number(item));
          } else if (key === 'pv') {
            Object.keys(val).forEach((valKey) => {
              val[valKey] = val[valKey].map((el) => Number(el));
            });
          }

          this.form[key] = val;
        }
      });
    },
  },
};
</script>

<style scoped>

</style>
