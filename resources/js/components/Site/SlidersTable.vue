<template>
  <el-table
    :data="sliders"
    class="w-100"
    @row-click="openSlider"
  >
    <el-table-column
      label="Изображение"
      width="140"
    >
      <template #default="scope">
        <el-row>
          <el-image
            v-if="scope.row.image"
            style="width: 120px; height: 80px"
            :src="scope.row.image.thumb_2x"
          />
        </el-row>
      </template>
    </el-table-column>

    <el-table-column
      label="Информация"
    >
      <template #default="scope">
        <el-row>
          <div class="slider-info">
            <p>
              {{ scope.row.title }}
            </p>
            <p> {{ scope.row.description }} </p>
            <p>
              Ссылка для перехода: <a
                :href="scope.row.url"
                target="_blank"
                @click.stop
              >{{ scope.row.url }}</a>
            </p>
          </div>
        </el-row>
      </template>
    </el-table-column>

    <el-table-column
      prop="active"
      label="Активен"
      width="100"
    >
      <template #default="scope">
        <div>
          {{ scope.row.active ? 'Да' : 'Нет' }}
        </div>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'SlidersTable',
  props: {
    sliders: {
      type: Array,
      required: true,
    },
  },

  methods: {
    openSlider(row) {
      // eslint-disable-next-line no-unused-vars
      const [__, action] = this.route().current().split('.');

      this.$inertia.visit(route(`site.${action}.edit`, row.id));
    },
  },
};
</script>

<style scoped>
  .slider-info p {
    margin: 0;
  }
</style>
