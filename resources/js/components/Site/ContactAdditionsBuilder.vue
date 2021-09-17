<template>
  <div>
    <pre>{{ additions }}</pre>
    <div
      v-for="(addition, index) of additions"
      :key="index"
    >
      <el-form-item
        v-if="addition.type === 'phone'"
        label="Телефон"
        prop="name"
      >
        <el-input
          :model-value="addition.value"
          @input="(value) => input(index, value)"
          @clear="() => clear(index)"
        />
        <el-button
          type="danger"
          class="mt-2"
          size="mini"
          @click="() => deleteItem(index)"
        >
          Удалить
        </el-button>
      </el-form-item>
    </div>
    <el-button
      type="primary"
      size="mini"
      @click="createItem"
    >
      Добавить
    </el-button>
  </div>
</template>

<script>
export default {
  name: 'ContactAdditionsBuilder',
  props: {
    additions: Object,
  },

  methods: {
    input(index, value) {
      const { additions } = this;

      additions[index].value = value;

      this.$emit('update:additions', additions);
    },

    clear(index) {
      const { additions } = this;

      additions[index].value = additions[index].value.slice(-1);

      this.$emit('update:additions', additions);
    },

    createItem() {
      const { additions } = this;

      additions.push({
        type: 'phone',
        value: '',
      });

      this.$emit('update:additions', additions);
    },

    deleteItem(index) {
      const { additions } = this;

      additions.splice(index, 1);

      this.$emit('update:additions', additions);
    },
  },
};
</script>

<style scoped>

</style>
