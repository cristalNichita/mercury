<template>
  <el-table
    class="w-100"
    row-key="row_id"
    :data="contacts"
  >

    <el-table-column
      prop="name"
      label="Наименование"
    >
      <template #default="scope">
        <div class="text-truncate">
          <inertia-link :href="route('users.contacts.show', scope.row.id)">
            {{ scope.row.name || 'Без названия' }}
          </inertia-link>
        </div>
      </template>
    </el-table-column>

    <el-table-column
      prop="position"
      label="Должность"
      width="300"
    />

    <el-table-column
      prop="user"
      label="Пользователь"
      loading="updateOwnerLoading"
    >
      <template #default="scope">
        <div
          v-if="scope.row.user"
          class="text-truncate"
        >
          <inertia-link :href="route('users.edit', scope.row.user.id)">
            {{ scope.row.user.name || 'Без названия' }}
          </inertia-link>
        </div>
        <div v-else>
          <el-button
            type="primary"
            icon="el-icon-plus"
            @click="inviteUser(scope.row.id)"
          >
            Пригласить пользователя
          </el-button>
        </div>
      </template>
    </el-table-column>

  </el-table>
</template>

<script>
export default {
  name: 'HoldingContactsTable',
  props: {
    contacts: {
      type: Array,
      required: true,
    },
  },
  data () {
    return {
      updateOwnerLoading: false,
    }
  },
  methods: {
    inviteUser(contact_id) {
      this.updateOwnerLoading = true;

      this.$inertia.post(route('users.contacts.invite', contact_id), {}, {
        onSuccess: () => {
          this.$notify.success({
            title: 'Успешно',
            message: 'Приглашение успешно отправленно',
          });
        },
        onError: (errors) => {
          Object.values(errors).forEach(value => {
            this.$notify.error({
              title: 'Ошибка',
              message: value,
            });
          });
        },
        onFinish: () => {
          this.inviteUserLoading = false;
        },
      });
    },
  },
};
</script>
