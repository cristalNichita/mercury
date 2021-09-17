<template>
  <el-table

    class="w-100"
    default-expand-all
    row-key="row_id"
    :data="holdings"
    :default-sort="defaultSort"
    :span-method="arraySpanMethod"
    :row-class-name="tableRowClassName"
    @sort-change="sortChange"
  >
    <el-table-column
      prop="title"
      label="Наименование"
    >
      <template #default="scope">
        <span :class="{'font-weight-bold':scope.row.row_type==='s-grid'||scope.row.is_owner}">
          <i :class="`el-icon-${scope.row.row_type} mr-2`"/>

          <inertia-link
            v-if="scope.row.route"
            :href="route(scope.row.route, scope.row.id)"
          >
            {{ scope.row.name || 'Без названия' }}
          </inertia-link>
          <template v-else>
            {{ scope.row.name || 'Без названия' }}
          </template>
        </span>
      </template>
    </el-table-column>
    <el-table-column
      prop="type"
      label="Тип"
      width="300"
    />
    <el-table-column
      prop="inn"
      width="140"
      label="ИНН"
    />
    <slot/>
  </el-table>
</template>

<script>
  export default {
    name: 'HoldingsTable',
    props: {
      holdings: {
        type: Array,
        required: true,
      },
    },
    computed: {
      // eslint-disable-next-line consistent-return,vue/return-in-computed-property
      defaultSort() {
        if (this.$page.props.sort) {
          const [prop, order] = this.$page.props.sort.split('-');
          return {prop, order};
        }
      },
    },
    methods: {
      sortChange(column) {
        let sort;
        if (column.prop) {
          sort = `${column.prop}-${column.order}`;
        }

        this.$inertia.replace(route(route().current(), route().params), {
          method: 'get',
          data: {sort},
          replace: false,
          preserveState: true,
          preserveScroll: false,
          onBefore: () => {
            this.$emit('update:loading', true);
          },
          onFinish: () => {
            this.$emit('update:loading', false);
          },
        });
      },

      arraySpanMethod({row}) {
        if (row.row_type === 's-grid') {
          return [1, 2];
        }
        return [1, 1];
      },
      tableRowClassName({row}) {
        if (row.row_type === 's-grid') {
          return 'b-holding__row';
        }
        return '';
      },
    },
  };
</script>

<style scoped lang="scss">
  .b-holding {
    &__row {
      background: oldlace;
    }

    &__name {
      display: block;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
  }
</style>
