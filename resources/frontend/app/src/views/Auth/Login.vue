<template>
  <div class="auth-container">
    <form class="auth-form">
      <div style="text-align: center;">
        <router-link to="/">
          <img class="logo" alt="Vue logo" src="../../assets/logo.png">
        </router-link>
      </div>
      
      <div>
        <label for="email">Email</label>
        <input type="text" placeholder="Enter Email" name="email" id="email" required v-model="login_form.username">
      </div>
      <div>
        <label for="psw">Password</label>
        <input type="password" placeholder="Enter Password" name="psw" id="psw" required v-model="login_form.password">
      </div>

      <div v-if="validation_errors">
          <ul class="error-message">
              <li v-for="(value, key, index) in validation_errors">{{ value }}</li>
          </ul>
      </div>

      <div class="form-footer">
        <router-link to="/register" style="margin-right: 10px;">Register</router-link>
        <button type="submit" class="btn" @click="submit">Login</button>
      </div>
    </form>
  </div>
</template>

<script>
import {mapState, mapGetters, mapActions} from 'vuex'
export default {
  name: 'Login',
  components: {
  },
  data(){
    return {
      login_form: {
        username: "",
        password: ""
      },
      validation_errors: [],
      // errors: {}
    }
  },
  computed: {
    
  },
  methods: {
    submit(e) {
      e.preventDefault()

      this.$store.dispatch('user/retrieveToken', this.login_form)
      .then(response => {
        console.log(response.data)
      })
      .catch(error => {
        console.log(error.response.data)
      })
      // axios.post('auth/login', this.login_form)
      // .then((data) => {
        
      // })
      // .catch((error) => {
      //   if (error.response.status == 422){
      //     let errors = Object.values(error.response.data.error);
      //     errors = errors.flat();
      //     this.validation_errors = errors;
      //   } else if(error.response.status == 400){
      //     this.validation_errors.push(error.response.data);
      //   }
      // })
    },
  }
};

</script>

<style scoped lang="scss">
  
</style>
