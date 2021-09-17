<template>
  <div>
    <template v-if="!order.may_payment">
      <el-tag type="danger">
        Запрет оплаты
      </el-tag>
    </template>
    <template v-else>
      <order-payment-status
        v-if="order.payment"
        :status="order.payment.status"
      />
    </template>
  </div>
</template>

<script>
import OrderPaymentStatus from '@modules/Order/Resources/OrderPaymentStatus';

export default {
  name: 'OrderPaymentInfo',
  components: { OrderPaymentStatus },
  props: {
    order: {
      type: Object,
      required: true,
    },
  },
};
</script>
