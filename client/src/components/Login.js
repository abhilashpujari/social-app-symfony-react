import React, { Component } from 'react';
import {
  Container,
  Form,
  Button
} from 'react-bootstrap';

import logo from '../logo.png';
import '../styles/components/login.scss';

class Login extends Component {
  constructor(props) {
    super(props);

    this.state = {
      email: "",
      password: ""
    };
  }

  render() {
    const { email, password } = this.state;

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
                <Form.Control type="email" placeholder="test@gmail.com" value={email} />
              </Form.Group>

              <Form.Group controlId="password">
                <Form.Label>Password</Form.Label>
                <Form.Control type="password" placeholder="Password" value={password} />
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