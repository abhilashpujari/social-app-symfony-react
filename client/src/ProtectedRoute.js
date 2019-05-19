import React from "react";
import {
  Route,
  Redirect
} from "react-router-dom";

import jwtDecode from 'jwt-decode';

const checkAuth = () => {
  let token = localStorage.getItem('token');

  if (!token) {
    return false;
  }

  try {
    const { exp } = jwtDecode(token);

    if (exp < (Date.now() / 1000)) {
      return false;
    }
  } catch (e) {
    return false;
  }

  return true;
};

function ProtectedRoute({ component: Component, ...rest }) {
  return (
    <Route
      {...rest}
      render={props =>
        checkAuth() ? (
          <Component {...props} />
        ) : (
            <Redirect
              to={{
                pathname: "/"
              }}
            />
          )
      }
    />
  );
}

export default ProtectedRoute;