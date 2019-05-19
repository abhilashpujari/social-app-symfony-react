import React from 'react'
import { Route, Switch } from 'react-router-dom';
import ProtectedRoute from './ProtectedRoute';

import Login from './components/Auth/Login';
import SignUp from './components/Auth/SignUp';
import ForgotPassword from './components/Password/ForgotPassword';
import ChangePassword from './components/Password/ChangePassword';
import PageNotFound from './components/PageNotFound';
import Home from './components/Home';

const Routes = () => (
  <Switch>
    <ProtectedRoute exact path="/home" component={Home} />
    <Route exact path="/" component={Login} />
    <Route exact path="/change-password/:token" component={ChangePassword} />
    <Route exact path="/forgot-password" component={ForgotPassword} />
    <Route exact path="/signup" component={SignUp} />
    <Route path="*" component={PageNotFound} />
  </Switch>
);

export default Routes;
