<template>
  <div>
    <template v-if="delivery">
      <div>
        СДЭК
        <span v-if="delivery.type=='pickUp'">
          в ПВЗ
          <span
            v-if="delivery.pvz"
            class="font-weight-bold"
          >
            {{ delivery.pvz.id || '' }}
          </span>
          <div>
            {{ pvzAddress }}
          </div>
        </span>
      </div>
      <div
        v-if="order.delivery_cost"
        class="text-primary font-weight-bold"
      >
        Стоимость: {{ $filters.moneyFormat(order.delivery_cost) }} р.
      </div>
      <pre>{{ order.delivery }}</pre>
    </template>
    <template v-else>
      Отсутсвует
    </template>
  </div>
</template>

<script>
export default {
  name: 'OrderDeliveryInfo',
  props: {
    order: {
      type: Object,
      required: true,
    },
  },
  computed: {
    delivery() {
      return this.order.delivery ?? null;
    },
    pvzAddress() {
      if (!this.delivery) {
        return '';
      }
      const result = [];

      if (this.delivery.zip) {
        result.push(this.delivery.zip);
      }
      if (this.delivery.region) {
        result.push(this.delivery.region);
      }
      if (this.delivery.city) {
        result.push(this.delivery.city);
      }
      if (this.delivery.pvz?.Address) {
        result.push(this.delivery.pvz.Address);
      }
      return result.join(', ');
    },
  },
};
</script>
