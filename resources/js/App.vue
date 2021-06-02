<template>
  <p>mamana aa {{ name }}</p>
</template>

<script>
import { ref } from 'vue';
export default {
  setup() {
    let name = ref('John ikuru')

    return {name};
  },
};
</script>

<style>
</style>