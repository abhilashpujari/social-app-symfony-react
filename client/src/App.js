import React from 'react';
import './styles/global.scss';
import 'bootstrap/dist/css/bootstrap.min.css';
import { BrowserRouter as Router } from 'react-router-dom';
import Routes from './Routes';

function App() {
  return (
    <Router>
      <Routes />
    </Router>
  );
}

export default App;
