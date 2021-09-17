<template>
  <el-table
    :data="blocks"
    class="w-100"
    @row-click="openBlock"
  >
    <el-table-column
      label="Заголовок"
    >
      <template #default="scope">
        <div>
          {{ scope.row.title }}
        </div>
        <a
          v-if="scope.row.page !== null"
          :href="route('site.info.edit', scope.row.page.id)"
        >
          Страница
        </a>
      </template>
    </el-table-column>
    <el-table-column
      label="Описание"
    >
      <template #default="scope">
        {{ scope.row.description }}
      </template>
    </el-table-column>
    <el-table-column
      prop="background_color"
      label="Цвет"
      width="100"
    >
      <template #default="scope">
        <div
          :style="`width: 30px; height: 30px; background-color: ${scope.row.background_color}`"
        />
      </template>
    </el-table-column>
    <el-table-column
      prop="slug"
      label="Символьный код"
      width="100"
    >
      <template #default="scope">
        <div>
          {{ scope.row.slug }}
        </div>
      </template>
    </el-table-column>
    <el-table-column
      label="На главной"
      width="100"
      align="center"
    >
      <template #default="scope">
        <el-checkbox
          :model-value="!!scope.row.in_main"
          :disabled="isToggling"
          @click.stop="() => toggleMain(scope.row.id)"
        />
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'InfoBlocksTable',
  props: {
    blocks: Array,
  },
  data() {
    return ({
      isToggling: false,
    });
  },
  methods: {
    openBlock(row) {
      this.$inertia.visit(route('site.info-blocks.edit', row.id));
    },

    toggleMain(id) {
      if (this.isToggling) {
        return;
      }
      console.log(id);

      this.isToggling = true;
      this.$inertia.get(route('site.info-blocks.toggle-main', id), {}, {
        onFinish: () => {
          this.isToggling = false;
        },
        preserveState: true,
        preserveScroll: true,
      });
    },
  },
};
</script>

<style scoped>

</style>
