import React, { useEffect } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'toastr/build/toastr.min.css';
import './styles/global.scss';
import { BrowserRouter as Router } from 'react-router-dom';
import Routes from './Routes';
import Header from './components/Header';

import identity from './utils/identity';

function App() {
  
  useEffect(() => {
    if (identity.isAuthenticated()) {
      document.body.classList.add('authenticated-route');
    } else {
      document.body.classList.remove('authenticated-route');
    }
  }, []);

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
