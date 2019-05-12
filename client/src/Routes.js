import React from 'react'
import { Route, Switch } from 'react-router-dom';

import Login from './components/Login';
import PageNotFound from './components/PageNotFound';

const Routes = () => (
  <Switch>
    <Route exact path="/" component={Login}></Route>
    <Route path="*" component={PageNotFound}></Route>
  </Switch>
);

export default Routes;
