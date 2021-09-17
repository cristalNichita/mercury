<template>
  <el-button
    type="success"
    icon="el-icon-plus"
    size="mini"
    @click="createRequisite"
  >
    Добавить
  </el-button>
  <el-table
    :data="bankRequisites"
    class="w-100"
  >
    <el-table-column
      label="Наименование"
      props="name"
    >
      <template #default="scope">
        <div>
          {{ scope.row.name }}
        </div>
      </template>
    </el-table-column>
    <el-table-column
      label="БИК"
      props="bik"
    >
      <template #default="scope">
        {{ scope.row.bik }}
      </template>
    </el-table-column>
    <el-table-column
      label="Кор. счет"
      props="kor"
    >
      <template #default="scope">
        <div>
          {{ scope.row.kor }}
        </div>
      </template>
    </el-table-column>
    <el-table-column
      label="Счет"
      props="invoice"
    >
      <template #default="scope">
        <div>
          {{ scope.row.invoice }}
        </div>
      </template>
    </el-table-column>
    <el-table-column
      label="Закрыт"
      width="100"
      align="center"
    >
      <template #default="scope">
        <el-checkbox
          :model-value="!!scope.row.closed"
          :disabled="isTogglingClosed"
          @click.stop="() => toggleClosed(scope.row)"
        />
      </template>
    </el-table-column>
    <el-table-column
      label="Основной"
      width="100"
      align="center"
    >
      <template #default="scope">
        <el-checkbox
          :model-value="!!scope.row.default"
          :disabled="isTogglingDefault"
          @click.stop="() => toggleDefault(scope.row)"
        />
      </template>
    </el-table-column>
    <el-table-column
      width="100"
      align="center"
    >
      <template #default="scope">
        <el-button
          type="danger"
          size="mini"
          @click="() => { askDeleteConfirm = true; deleteRow = scope.row }"
        >
          Удалить
        </el-button>
      </template>
    </el-table-column>
  </el-table>

  <el-dialog
    v-model="askDeleteConfirm"
    title="Удаление банковского реквизита"
    width="30%"
    center
  >
    <span>Вы уверены, что хотите удалить данные реквизиты?</span>
    <template #footer>
      <el-button @click="() => { askDeleteConfirm = false; deleteRow = {} }">
        Отмена
      </el-button>
      <el-button
        type="danger"
        @click="deleteItem(deleteRow)"
      >
        Да, удалить
      </el-button>
    </template>
  </el-dialog>
</template>

<script>
export default {
  name: 'CompanyBankRequisitesTable',
  props: {
    bankRequisites: {
      type: Array,
      required: true
    },
    company_id: {
      type: Number,
      required: true
    }
  },
  data() {
    return ({
      isTogglingClosed: false,
      isTogglingDefault: false,
      askDeleteConfirm: false,
      deleteRow: {}
    });
  },

  methods: {
    deleteItem(row) {
      this.askDeleteConfirm = false
      this.$inertia.delete(route('users.company.bank-requisites.destroy',
        {
          bank_requisite: row.id,
          company: row.company_id
        },
        {
          onSuccess: () => {
            this.$notify.success({
              title: 'Успешно',
              message: 'Реквизит удален!',
            });
          },
          onError: (errors) => {
            this.$notify.success({
              title: 'Ошибка',
              message: errors[0],
            });
          },
        }
      ));
    },

    createRequisite() {
      this.$inertia.visit(route('users.company.bank-requisites.create',
        {
          company: this.company_id
        })
      );
    },

    toggleClosed(row) {
      if (this.isTogglingClosed) {
        return;
      }

      this.isTogglingClosed = true;
      this.$inertia.get(route('users.company.bank-requisites.closed',
        {
          bank_requisite: row.id,
          company: row.company_id
        }),
        {},
        {
          onFinish: () => {
            this.isTogglingClosed = false;
          },
          preserveState: true,
          preserveScroll: true,
        });
    },

    toggleDefault(row) {
      if (this.isTogglingDefault) {
        return;
      }

      this.isTogglingDefault = true;
      this.$inertia.get(
        route('users.company.bank-requisites.default', {
          bank_requisite: row.id,
          company: row.company_id
        }),
        {},
        {
          onFinish: () => {
            this.isTogglingDefault = false;
          },
          onError: errors => {
            this.$notify.error({
              title: 'Ошибка при установке основных реквизитов',
              message: errors[0],
            });
          },
          preserveState: true,
          preserveScroll: true,
        }
      );
    },
  },
};
</script>

<style scoped>

</style>
