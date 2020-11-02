import Vue from 'vue'
import Vuex from 'vuex'
import user from './modules/user'
// import createLogger from '../../../src/plugins/logger'

Vue.use(Vuex)

// const debug = process.env.NODE_ENV !== 'production'

const store = new Vuex.Store ({
	modules: {
    user,
  },
})

export default store