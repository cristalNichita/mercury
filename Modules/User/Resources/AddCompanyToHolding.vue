<template>
  <el-form
    v-loading="addCompanyLoading"
    label-width="240px"
  >
    <el-form-item
      label="Быстрое добавление компании"
      prop="owner_id"
    >
      <el-input
        v-model="query"
        type="text"
        class="w-100"
        placeholder="Введите название или ИНН"
      />
    </el-form-item>

    <el-table
      v-if="results.length"
      class="w-100 mb-3"
      row-key="row_id"
      :data="results"
    >
      <el-table-column
        prop="name"
        label="Название"
      />

      <el-table-column
        prop="address"
        label="Адрес"
      />

      <el-table-column>
        <template #default="scope">
          <el-button
            type="primary"
            icon="el-icon-plus"
            @click="addCompany(scope.row)"
          >
            Добавить
          </el-button>
        </template>
      </el-table-column>
    </el-table>
  </el-form>
</template>

<script>
export default {
  name: 'CompanyDaDataSearch',
  props: {
    holding: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      query: null,
      results: [],
      addCompanyLoading: false,
      token: this.$page.props.dadata_token,
    };
  },
  watch: {
    query(after, before) {
      this.fetch();
    },
  },

  methods: {
    fetch() {
      this.$store.dispatch('dadata/company', { query: this.query, token: this.token }).then((suggestions) => {
        this.results = suggestions;
      });
    },
  },
};
</script>
