import React from 'react'
import { Switch } from 'react-router-dom';
import ProtectedRoute from './components/ProtectedRoute';

import Login from './components/Auth/Login';
import SignUp from './components/Auth/SignUp';
import ForgotPassword from './components/Password/ForgotPassword';
import ResetPassword from './components/Password/ResetPassword';
import PageNotFound from './components/PageNotFound';
import Home from './components/Home';
import PublicRoute from './components/PublicRoute';

import routeConfig from './routeConfig';

const Routes = () => (
  <Switch>
    <ProtectedRoute exact path={routeConfig.home} component={Home} />
    <PublicRoute exact path={routeConfig.login} component={Login} />
    <PublicRoute exact path={routeConfig.resetPassword} component={ResetPassword} />
    <PublicRoute exact path={routeConfig.forgotPassword} component={ForgotPassword} />
    <PublicRoute exact path={routeConfig.signup} component={SignUp} />
    <PublicRoute path="*" component={PageNotFound} />
  </Switch>
);

export default Routes;
