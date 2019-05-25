import React, { useEffect } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'toastr/build/toastr.min.css';
import './styles/global.scss';
import { BrowserRouter as Router } from 'react-router-dom';
import Routes from './Routes';

import flashMessenger from '../src/utils/flashMessenger';

function App() {

  useEffect(() => {
    let messenger = flashMessenger();

    // Update the document title using the browser API
    if (sessionStorage.getItem('messenger')) {
      var message = JSON.parse(sessionStorage.getItem('messenger'));
      //messenger.show(message.type, message.message, false);
      sessionStorage.removeItem('messenger');
    }
  });

  return (
    <Router>
      <Routes />
    </Router>
  );
}

export default App;
