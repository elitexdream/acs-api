<template>
  <div id="app">
    <component :is="layout">
      <router-view/>
    </component>
  </div>
</template>

<script>
const defaultLayout = 'default';
export default {
  name: 'App',
  computed: {
    layout() {
      return `${this.$route.meta.layout || defaultLayout}-layout`;
    },
  },
  data() {
    return {
    };
  },
};
</script>

<style lang="scss">
@import './assets/styles.css';
* {
  box-sizing: border-box
}
body {
  margin: 0,
}
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}
#nav {
  padding: 30px;
  a {
    font-weight: bold;
    color: #2c3e50;
    &.router-link-exact-active {
      color: #42b983;
    }
  }
}
.logo {
  width: 40px;
  height: 40px;
}
.auth-container{
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: lightslategrey;
  .auth-form{
    background: white;
    width: 500px;
    border: solid 1px grey;
    padding: 20px;
  }
  .form-footer{
    text-align: right;
  }
}
</style>
