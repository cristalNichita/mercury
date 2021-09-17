<template>
  <div>
    <div
      v-for="(param, index) in preparedParams"
      :key="index"
    >
      <div class="h5">
        {{ param.title }}
      </div>
      <ul class="list-unstyled">
        <li
          v-for="item in param.items"
          :key="item.id"
        >
          <a
            v-if="item.type==='email'"
            :href="`mailto:${item.value}`"
          >
            {{ item.value }}
          </a>
          <a
            v-else-if="item.type==='phone'"
            :href="`tel:+${item.value.replace(/\D+/gm, '')}`"
          >
            {{ item.value }}
          </a>
          <span v-else>
            {{ item.value }}
          </span>

          <span
            v-if="item.view"
            class="text-muted"
          >
            ({{ item.view }})
          </span>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ContactParams',
  props: {
    params: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      paramName: {
        email: 'Email',
        phone: 'Телефон',
        address: 'Адрес',
      },
    };
  },
  computed: {
    preparedParams() {
      const result = {};

      this.params.forEach((item) => {
        console.log(item);
        if (!result[item.type]) {
          result[item.type] = {
            title: this.paramName[item.type] ?? item.type,
            items: [],
          };
        }
        item.value = item.value_1c ?? item.value;
        result[item.type].items.push(item);
      });
      return result;
    },
  },
};
</script>

<style scoped>

</style>
