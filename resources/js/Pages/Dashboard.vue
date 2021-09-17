<template>
  <base-layout>
    {{ $page.props.user }}
  </base-layout>
</template>

<script>
import BaseLayout from '@/Layouts/BaseLayout';

export default {
  components: {
    BaseLayout,
  },
};
</script>
