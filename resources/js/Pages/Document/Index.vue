<template>
  <div>
    <div class="bg-white shadow-sm">
      <el-table
        ref="table"
        :data="documents.data"
        class="w-100"
        :default-sort="defaultSort"
        :row-class-name="tableRowClassName"
        @row-click="rowClick"
        @sort-change="rowSortChange"
      >
        <el-table-column
          prop="id"
          label="ID/№"
          sortable="custom"
        >
          <template #default="scope">
            #{{ scope.row.id }}
          </template>
        </el-table-column>

        <el-table-column
          prop="status"
          label="Статус"
        >
          <template #default="scope">
            <el-tag
              :type="scope.row.status == 1 ?
                'danger'
                : scope.row.status == 2
                  ? 'info'
                  : scope.row.status == 3
                    ? 'warning'
                    : scope.row.status == 4
                      ? 'success'
                      : ''"
            >
              {{ scope.row.state }}
            </el-tag>
          </template>
        </el-table-column>

        <el-table-column
          prop="created_at"
          label="Создан"
        >
          <template #default="scope">
            <i class="el-icon-time" />
            <span style="margin-left: 10px">{{ $filters.timeFormat(scope.row.created_at) }}</span>
          </template>
        </el-table-column>
        <el-table-column
          prop="updated_at"
          label="Обновлен"
        >
          <template #default="scope">
            <i class="el-icon-time" />
            <span style="margin-left: 10px">{{ $filters.timeFormat(scope.row.updated_at) }}</span>
          </template>
        </el-table-column>
      </el-table>
      <div class="pt-2 pb-2">
        <ui-pagination
          :max="Math.round(documents.total/documents.per_page)"
          :page="currentPage"
        />
      </div>
    </div>
  </div>
</template>

<script>
import UiPagination from '@/components/UI/UiPagination';
import UserLayout from '@/Layouts/UserLayout';

export default {
  name: 'Documents',
  components: { UiPagination },
  layout: (h, page) => h(UserLayout, [page]),
  props: {
    documents: Array,
    sort: String,
  },
  data() {
    return {
      currentPage: this.documents.current_page,
    };
  },
  computed: {
    // eslint-disable-next-line consistent-return,vue/return-in-computed-property
    defaultSort() {
      if (this.$page.props.sort) {
        const sort = this.$page.props.sort.split('-');
        return { prop: sort[0], document: sort[1] };
      }
    },
  },
  methods: {
    // eslint-disable-next-line vue/no-dupe-keys
    sort() {
      console.log('');
    },
    rowSortChange(column) {
      if (column.prop === null) {
        this.$inertia.get(this.route('documents', false, { preserveState: true }));
      } else {
        this.$inertia.get(this.route('documents', { sort: `${column.prop }-${ column.document}` }, { preserveState: true }));
      }
    },
    handleCurrentChange(val) {
      if (this.$page.props.sort) {
        const sort = this.$page.props.sort.split('-');
        this.$inertia.visit(this.route('documents', { page: val, sort: `${sort[0] }-${ sort[1]}` }));
      } else {
        this.$inertia.visit(this.route('documents', { page: val }));
      }
    },
    handleEdit(index, row) {
      console.log(index, row);
    },
    handleDelete(index, row) {
      console.log(index, row);
    },
    rowClick(index) {
      this.$inertia.visit(this.route('users.documents.show', index.id));
    },
    tableRowClassName({ row }) {
      if (row.user.partner) {
        return 'warning-row';
      }

      return '';
    },
  },
};
</script>

<style scoped>
  .el-table tr.warning-row {
    background: #dd6161;
    color: #fff;
  }
  .el-table tr.warning-row:hover {
    color: #000;
  }
</style>
