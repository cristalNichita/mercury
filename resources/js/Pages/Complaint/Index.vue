<template>
  <div>
    <div class="bg-white shadow-sm">
      <el-table
        ref="table"
        :data="complaints.data"
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
          prop="description"
          label="Описание"
        />

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
          :max="Math.round(complaints.total/complaints.per_page)"
          :page="currentPage"
        />
      </div>
    </div>
  </div>
</template>

<script>
import UiPagination from '@/components/UI/UiPagination';
import ComplaintLayout from '@/Layouts/ComplaintLayout';

export default {
  name: 'Complaints',
  components: { UiPagination },
  layout: (h, page) => h(ComplaintLayout, [page]),
  props: {
    complaints: Array,
    sort: String,
  },
  data() {
    return {
      currentPage: this.complaints.current_page,
    };
  },
  computed: {
    // eslint-disable-next-line consistent-return,vue/return-in-computed-property
    defaultSort() {
      if (this.$page.props.sort) {
        const sort = this.$page.props.sort.split('-');
        return { prop: sort[0], complaint: sort[1] };
      }
    },
  },
  methods: {
    // eslint-disable-next-line vue/no-dupe-keys
    sort() {
      console.log('wer');
    },
    rowSortChange(column) {
      if (column.prop === null) {
        this.$inertia.get(this.route('complaints', false, { preserveState: true }));
      } else {
        this.$inertia.get(this.route('complaints', { sort: `${column.prop }-${ column.complaint}` }, { preserveState: true }));
      }
    },
    handleCurrentChange(val) {
      if (this.$page.props.sort) {
        const sort = this.$page.props.sort.split('-');
        this.$inertia.visit(this.route('complaints', { page: val, sort: `${sort[0] }-${ sort[1]}` }));
      } else {
        this.$inertia.visit(this.route('complaints', { page: val }));
      }
    },
    handleEdit(index, row) {
      console.log(index, row);
    },
    handleDelete(index, row) {
      console.log(index, row);
    },
    rowClick(index) {
      this.$inertia.visit(this.route('complaints.show', index.id));
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
