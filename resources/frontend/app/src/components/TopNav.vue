<template>
  <div class="nav">
    <div>
      <img class="logo" alt="Vue logo" src="../assets/logo.png">
    </div>
    <div class="nav-links">
      <router-link to="/login">Login</router-link> |
      <router-link to="/register">Register</router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TopNav',
  props: {
  },
};
</script>

<style scoped lang="scss">
  .nav{
    display: flex;
    justify-content: space-between;
    padding: 4px 100px;
    background: lightslategrey;
    align-items: center;
    color: white;
    a{
      color: white;
      text-decoration: none;
    }
  }
</style>
