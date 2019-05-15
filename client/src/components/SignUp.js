import React, { Component } from 'react';
import {
  Container,
  Form,
  Button
} from 'react-bootstrap';

import { Link } from "react-router-dom";

import logo from '../logo.png';
import '../styles/components/sign-up.scss';

class SignUp extends Component {
  constructor(props) {
    super(props);

    this.state = {
      email: "",
      password: ""
    };
  }

  handleChange = (e) => {
    this.setState({ [e.target.name]: e.target.value });
  }

  render() {
    const { email, password } = this.state;

    return (
      <Container>
        <div className="sign-up">
          <div className="sign-up__box">
            <div className="logo__container text-center">
              <img className="logo" src={logo} alt="Logo" />
            </div>
            <Form className="sign-up__form">
              <Form.Group controlId="email">
                <Form.Label>Email</Form.Label>
                <Form.Control type="email" name="email" placeholder="test@gmail.com" value={email} onChange={this.handleChange} />
              </Form.Group>

              <Form.Group controlId="password">
                <Form.Label>Password</Form.Label>
                <Form.Control type="password" name="password" placeholder="Password" value={password} onChange={this.handleChange} />
              </Form.Group>
              <Form.Group>
                <Button variant="primary" type="submit" block>
                  Sign Up
                </Button>
              </Form.Group>
              <Form.Group className="text-center">
                Already have an account
                <Link to="/">&nbsp;Login</Link>
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

export default SignUp;