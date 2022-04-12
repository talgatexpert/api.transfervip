import axios from "axios";


export const state = () => ({
  reviews: []
})
export const mutations = {
  SET_REVIEWS: (state, payload) => {
    state.reviews = payload;
  },
}
function randomDate(start, end) {
  return new Date(start.getTime() + Math.random() * (end.getTime() - start.getTime()));
}
export const actions = {

  LOAD_REVIEWS: async ({commit}, payload) => {
    let reviews = await axios.get('https://jsonplaceholder.typicode.com/posts');
    reviews = reviews.data.map(item => {
      item['name'] = Math.floor(Math.random() * 10000) + 1
      item['rating'] = Math.floor(Math.random() * 10) + 1
      let date = randomDate(new Date(2012, 0, 1), new Date())
      item['date'] = date.getDate() + '.' + date.getMonth() + '.' + date.getFullYear()
      return item
    });
    commit('SET_REVIEWS', reviews)
  }

}
export const getters = {
  getReviews: state => state.reviews,
}
