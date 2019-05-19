import React from 'react'
import { Route, Switch } from 'react-router-dom';

import Login from './components/auth/Login';
import SignUp from './components/auth/SignUp';
import ForgotPassword from './components/password/ForgotPassword';
import ChangePassword from './components/password/ChangePassword';
import PageNotFound from './components/PageNotFound';

const Routes = () => (
  <Switch>
    <Route exact path="/" component={Login}></Route>
    <Route exact path="/change-password/:token" component={ChangePassword}></Route>
    <Route exact path="/forgot-password" component={ForgotPassword}></Route>
    <Route exact path="/signup" component={SignUp}></Route>
    <Route path="*" component={PageNotFound}></Route>
  </Switch>
);

export default Routes;
