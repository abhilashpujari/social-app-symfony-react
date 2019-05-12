import React from 'react';
import './styles/global.scss';
import 'bootstrap/dist/css/bootstrap.min.css';
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';
import Login from './components/Login';
import PageNotFound from './components/PageNotFound';

function App() {
  return (
    <Router>
      <Switch>
        <Route exact path="/" component={Login}></Route>
        <Route component={PageNotFound}></Route>
      </Switch>
    </Router>
  );
}

export default App;
