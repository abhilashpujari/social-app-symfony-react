import axios from 'axios';

const api = () => {
  let send = (endpoint, url, method, options = {}) => {
    return axios.request({
      baseURL: endpoint,
      url: url,
      method: method
    });
  };

  return {
    get(endpoint, url, data, options = {}) {
      send(endpoint, url, 'get', options);
    },
    post(endpoint, url, data, options = {}) {
      send(endpoint, url, 'post', options);
    }
  }
};

export default api;