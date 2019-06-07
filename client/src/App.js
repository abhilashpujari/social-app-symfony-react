import React from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'toastr/build/toastr.min.css';
import './styles/global.scss';
import { BrowserRouter as Router } from 'react-router-dom';
import Routes from './Routes';
import Header from './components/Header';

function App() {
  return (
    <>
      <Header />
      <Router>
        <Routes />
      </Router>
    </>
  );
}

export default App;
