import jwtDecode from 'jwt-decode';

const identity = () => {
  return {
    clearIdentity() {
      localStorage.removeItem('token');
      return true;
    },
    getToken() {
      return localStorage.getItem('token');
    },
    getIdentity() {
      let token = localStorage.getItem('token');

      if (!token) {
        return false;
      }

      try {
        return jwtDecode(token);
      } catch (e) {
        return false;
      }
    },
    isAuthenticated() {
      return (this.getIdentity() !== false);
    },
    setToken(token) {
      localStorage.setItem('token', token);
    }
  }
}

export default identity();