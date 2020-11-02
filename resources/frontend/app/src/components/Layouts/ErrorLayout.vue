<template>
  <div class="error-layout-container">
    <slot />
  </div>
</template>

<script>
export default {
  name: 'ErrorLayout',
};

</script>

<style scoped lang="scss">
	.error-layout-container {
		height: 100vh;
		display: flex;
		align-items: center;
		justify-content: center;
	}
</style>
