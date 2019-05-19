import axios from 'axios';
import identity from './identity';

const api = () => {
  let _send = async (endpoint, url, method, options = {}) => {
    let headers;

    if (identity.getToken()) {
      headers = {
        'X-Auth-Token': identity.getToken()
      };
    }

    let requestOptions = {
      baseURL: endpoint,
      url: url,
      method: method,
      headers: headers,
    };

    Object.assign(requestOptions, options);

    try {
      const response = await axios
        .request(requestOptions);

      if (response.headers.headers('X-Auth-Token')) {
        identity.setToken(response.headers.headers('X-Auth-Token'));
      }

      return Promise.resolve(response.data);
    } catch (error) {
      let errorMessage = 'Internal Server error';
      if (error.response && (error.response.status === 401 || error.response.status === 403)) {
        identity.clearIdentity();
        //window.location = '/';
      }

      console.log(error.response);
      if (error.response && error.response.data.error) {
        errorMessage = error.response.data.error;
      }
      return Promise.reject(errorMessage);
    }
  };

  return {
    get(endpoint, url, params = {}, options = {}) {
      let defaultOptions = {
        params: params
      };

      Object.assign(options, defaultOptions);
      return _send(endpoint, url, 'get', options);
    },
    post(endpoint, url, data = {}, options = {}) {
      let defaultOptions = {
        data: data
      };

      Object.assign(options, defaultOptions);
      return _send(endpoint, url, 'post', options);
    }
  }
}

export default api();