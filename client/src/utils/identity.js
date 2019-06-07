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
      if (this.getIdentity() === false) {
        return false;
      }

      const { exp, isActive } = this.getIdentity();
      if (!isActive || (exp < (new Date().getTime() + 1) / 1000)) {
        return false;
      }

      return true;
    },
    setToken(token) {
      localStorage.setItem('token', token);
    }
  }
}

export default identity();