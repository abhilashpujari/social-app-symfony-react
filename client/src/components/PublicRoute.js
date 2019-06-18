import React from "react";
import {
  Route,
  Redirect
} from "react-router-dom";

import identity from '../utils/identity';
import routeConfig from '../routeConfig';

function PublicRoute({ component: Component, ...rest }) {
  return (
    <Route
      {...rest}
      render={props =>
        !identity.isAuthenticated() ? (
          <Component {...props} />
        ) : (
            <Redirect
              to={{
                pathname: routeConfig.home
              }}
            />
          )
      }
    />
  );
}

export default PublicRoute;