import React, { Component } from 'react';
import {
  Container,
  Form,
  Button
} from 'react-bootstrap';

import { Link } from "react-router-dom";

import logo from '../logo.png';
import '../styles/components/forgot-password.scss';

class ForgotPassword extends Component {
  constructor(props) {
    super(props);

    this.state = {
      email: ""
    };
  }


  render() {
    const { email } = this.state;

    return (
      <Container>
        <div className="forgot-password">
          <div className="forgot-password__box">
            <div className="logo__container text-center">
              <img className="logo" src={logo} alt="Logo" />
            </div>
            <Form className="forgot-password__form">
              <Form.Group controlId="email">
                <Form.Label>Email</Form.Label>
                <Form.Control type="email" placeholder="test@gmail.com" value={email} onChange={(e) => this.setState({ password: e.target.value })} />
              </Form.Group>
              <Form.Group>
                <Button variant="primary" type="submit" block>
                  Sent Reset Link
                </Button>
              </Form.Group>

              <Form.Group className="text-center">
                <Link to="/">&nbsp;Login</Link>
              </Form.Group>
            </Form>
          </div>
        </div>
      </Container >
    );
  }
}

export default ForgotPassword;