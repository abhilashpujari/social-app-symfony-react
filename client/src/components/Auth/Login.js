import React, { Component } from 'react';
import {
  Container,
  Form,
  Button
} from 'react-bootstrap';

import { Link } from "react-router-dom";

import logo from '../../logo.png';
import '../../styles/components/login.scss';

import flashMessenger from '../../utils/flashMessenger';
import Validator from '../../utils/validator';

class Login extends Component {
  constructor(props) {
    super(props);

    this.state = {
      email: "",
      password: ""
    };

    this.flashMessenger = flashMessenger();
    this.validationRules = {
      password: 'required|min:6',
      email: 'required|email'
    };
  }

  handleChange = (e) => {
    this.setState({ [e.target.name]: e.target.value });
  }

  login = (e) => {
    e.preventDefault();
    let validator = new Validator(this.state, this.validationRules);

    if (validator.isValid()) {

    } else {
      this.flashMessenger.error(validator.getErrorMessages());
    }
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
                <Form.Control type="email" name="email" placeholder="test@gmail.com" value={email} onChange={this.handleChange} />
              </Form.Group>

              <Form.Group controlId="password">
                <Form.Label>Password</Form.Label>
                <Form.Control type="password" name="password" placeholder="Password" value={password} onChange={this.handleChange} />
              </Form.Group>
              <Form.Group>
                <Button variant="primary" type="submit" block onClick={this.login}>
                  Login
                </Button>
              </Form.Group>
              <Form.Group className="text-center">
                Don't have an account
                <Link to="/signup">&nbsp;Sign up</Link>
              </Form.Group>
              <Form.Group className="text-center">
                <Link to="/forgot-password">&nbsp;Forgot Password</Link>
              </Form.Group>
            </Form>
          </div>
        </div>
      </Container>
    );
  }
}

export default Login;