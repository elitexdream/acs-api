// initial state
// shape: [{ id, quantity }]
const state = () => ({
  status: 0,
  errors: []
})

// getters
const getters = {
  errorMessages: (state, getters, rootState) => {
    return state.errors;
  },
}

// actions
const actions = {
  setErrors ({ state, commit }, response) {
    commit('setErrors', response)
  }
}

// mutations
const mutations = {
  setErrors(state, response) {
    if (response.status == 422){
      let errors = Object.values(response.data.error);
      errors = errors.flat();
      state.errors = errors;
    } else if(response.status == 400){
      this.errors = [response.data];
    }
  },
}

export default {
  namespaced: true,

  state,
  getters,
  actions,
  mutations
}