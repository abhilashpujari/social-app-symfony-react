import React, { Component } from 'react';
import {
  Container,
  Form,
  Button
} from 'react-bootstrap';

import logo from '../logo.png';
import '../styles/components/login.scss';

class Login extends Component {
  render() {
    return (
      <Container>
        <div className="login">
          <div className="login__box">
            <div className="logo__container text-center">
              <img className="logo" src={logo} alt="Logo" />
            </div>
            <Form className="login__form">
              <Form.Group controlId="email">
                <Form.Label>Email</Form.Label>
                <Form.Control type="email" placeholder="test@gmail.com" />
              </Form.Group>

              <Form.Group controlId="password">
                <Form.Label>Password</Form.Label>
                <Form.Control type="password" placeholder="Password" />
              </Form.Group>
              <Button variant="primary" type="submit" block>
                Login
              </Button>
            </Form>
          </div>
        </div>
      </Container>
    );
  }
}

export default Login;