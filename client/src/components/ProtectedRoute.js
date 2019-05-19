import React from "react";
import {
  Route,
  Redirect
} from "react-router-dom";

import identity from '../utils/identity';

function ProtectedRoute({ component: Component, ...rest }) {
  return (
    <Route
      {...rest}
      render={props =>
        identity.isAuthenticated() ? (
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