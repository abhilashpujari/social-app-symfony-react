import React from 'react'
import { Route, Switch } from 'react-router-dom';

import Login from './components/Login';
import PageNotFound from './components/PageNotFound';
import SignUp from './components/SignUp';
import ForgotPassword from './components/ForgotPassword';
import ChangePassword from './components/ChangePassword';

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
