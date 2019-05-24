import React from 'react'
import { Route, Switch } from 'react-router-dom';
import ProtectedRoute from './components/ProtectedRoute';

import Login from './components/Auth/Login';
import SignUp from './components/Auth/SignUp';
import ForgotPassword from './components/Password/ForgotPassword';
import ChangePassword from './components/Password/ChangePassword';
import PageNotFound from './components/PageNotFound';
import Home from './components/Home';
import PublicRoute from './components/PublicRoute';

const Routes = () => (
  <Switch>
    <ProtectedRoute exact path="/home" component={Home} />
    <PublicRoute exact path="/" component={Login} />
    <PublicRoute exact path="/change-password/:token" component={ChangePassword} />
    <PublicRoute exact path="/forgot-password" component={ForgotPassword} />
    <PublicRoute exact path="/signup" component={SignUp} />
    <PublicRoute path="*" component={PageNotFound} />
  </Switch>
);

export default Routes;
