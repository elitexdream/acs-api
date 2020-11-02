<template>
  <div>
    <top-nav />
    <div>
      <slot />
    </div>
  </div>
</template>

<script>
import TopNav from '../TopNav.vue';

export default {
  name: 'DefaultLayout',
  components: {
    TopNav,
  },
};

</script>
