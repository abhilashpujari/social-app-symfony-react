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

      if (response.headers['x-auth-token']) {
        identity.setToken(response.headers['x-auth-token']);
      }

      return Promise.resolve(response.data);
    } catch (error) {
      let errorMessage = 'An Error Occured';
      if (error.response && error.response.data.error) {
        errorMessage = error.response.data.error;
      }

      if (error.response && (error.response.status === 419)) {
        identity.clearIdentity();
        window.location = '/';
      }

      return Promise.reject(errorMessage);
    }
  };

  return {
    delete(endpoint, url, params = {}, options = {}) {
      let defaultOptions = {
        params: params
      };

      Object.assign(options, defaultOptions);
      return _send(endpoint, url, 'delete', options);
    },
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
    },
    put(endpoint, url, data = {}, options = {}) {
      let defaultOptions = {
        data: data
      };

      Object.assign(options, defaultOptions);
      return _send(endpoint, url, 'put', options);
    }
  }
}

export default api();