<template>
  <div>
    <el-alert
      v-if="!tinymce_key"
      title="Добавте ключ TineMce в настройках системы"
      type="error"
      :closable="false"
    />
    <editor
      :api-key="tinymce_key"
      :model-value="modelValue"
      :init="{
        language: 'ru',
        height: height,
        paste_data_images: true,
        menubar: false,
        plugins: [
          'advlist autolink lists link image charmap print preview anchor',
          'searchreplace visualblocks code fullscreen',
          'insertdatetime media table paste code help wordcount'
        ],
        toolbar:
          'formatselect | bold italic | forecolor backcolor | \
           alignleft aligncenter alignright alignjustify | \
           link | bullist numlist | removeformat | code'
      }"
      @update:modelValue="onInput($event)"
    />
  </div>
</template>

<script>
import Editor from '@tinymce/tinymce-vue';

export default {
  name: 'UiEditor',
  components: { Editor },
  props: {
    modelValue: {
      type: String,
      required: true,
    },
    height: {
      type: Number,
      default: 400,
    },
  },
  computed: {
    tinymce_key() {
      return this.$page.props.tinymce_key;
    },
  },
  methods: {
    onInput(value) {
      this.$emit('update:modelValue', value);
    },
  },
};
</script>
