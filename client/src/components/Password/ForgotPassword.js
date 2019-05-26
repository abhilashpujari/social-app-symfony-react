import React, { Component } from 'react';
import {
  Container,
  Form,
  Button
} from 'react-bootstrap';

import { Link } from "react-router-dom";

import logo from '../../logo.png';
import '../../styles/components/forgot-password.scss';

import flashMessenger from '../../utils/flashMessenger';
import Validator from '../../utils/validator';
import api from '../../utils/api';
import config from '../../config/index';

class ForgotPassword extends Component {
  constructor(props) {
    super(props);

    this.state = {
      email: ""
    };

    this.validationRules = {
      email: 'required|email'
    };
  }

  handleChange = (e) => {
    this.setState({ [e.target.name]: e.target.value });
  }

  forgotPassword = (e) => {
    e.preventDefault();
    let validator = new Validator(this.state, this.validationRules);

    if (validator.isValid()) {
      api
        .post(`${config.endpoints.api}`, '/forgot-password', this.state)
        .then((response) => {
          flashMessenger.show('success', 'Reset link sent to your email');
          this.props.history.push('/');
        }).catch(error => flashMessenger.show('error', error.message));
    } else {
      flashMessenger.show('error', validator.getErrorMessages());
    }
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
                <Form.Control type="email" name="email" placeholder="test@gmail.com" value={email} onChange={this.handleChange} />
              </Form.Group>
              <Form.Group>
                <Button variant="primary" type="submit" block onClick={this.forgotPassword}>
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