import React, { Component } from 'react';
import {
  Container,
  Form,
  Button
} from 'react-bootstrap';
import { GoogleLogin } from 'react-google-login';
import { Link } from "react-router-dom";

import '../../styles/components/login.scss';

import flashMessenger from '../../utils/flashMessenger';
import Validator from '../../utils/validator';
import api from '../../utils/api';
import config from '../../config/index';
import routeConfig from '../../routeConfig';

class Login extends Component {
  constructor(props) {
    super(props);

    this.state = {
      formData: {
        email: "",
        password: ""
      },
      isButtonLoading: false
    };

    this.validationRules = {
      password: 'required|min:6',
      email: 'required|email'
    };
  }

  handleChange = (e) => {
    let formData = { ...this.state.formData };
    formData[e.target.name] = e.target.value;
    this.setState({
      formData
    });
  }

  responseGoogle = (response) => {
    console.log(response);
    if (response && response.tokenId) {
      const { tokenId } = response;

      api
        .post(`${config.endpoints.api}`, '/social-login', { provider_type: 'google', token: tokenId })
        .then((response) => {
          window.location.href = routeConfig.home;
        }).catch(error => {
          flashMessenger.show('error', error.message);
        });
    }
  }

  login = (e) => {
    e.preventDefault();
    let validator = new Validator(this.state.formData, this.validationRules);

    if (validator.isValid()) {
      this.setState({ isButtonLoading: true }, () => {
        api
          .post(`${config.endpoints.api}`, '/authenticate', this.state.formData)
          .then((response) => {
            this.setState({ isButtonLoading: false });
            window.location.href = routeConfig.home;
          }).catch(error => {
            this.setState({ isButtonLoading: false });
            flashMessenger.show('error', error.message);
          });
      });
    } else {
      flashMessenger.show('error', validator.getErrorMessages());
    }
  }

  render() {
    const { email, password } = this.state.formData;
    const { isButtonLoading } = this.state;

    return (
      <Container>
        <div className="login">
          <div className="login__box">
            <div className="logo__container text-center">
              <img className="logo" src="/logo.png" alt="Logo" />
            </div>
            <Form.Group className="text-center">
              <GoogleLogin
                clientId={config.google.clientId}
                buttonText="Login with Google"
                onSuccess={this.responseGoogle}
                onFailure={this.responseGoogle}
              />
            </Form.Group>
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
                <Button variant="primary" type="submit" block disabled={isButtonLoading} onClick={!isButtonLoading ? this.login : null}>
                  {isButtonLoading ? 'Login...' : 'Login'}
                </Button>
              </Form.Group>
              <Form.Group className="text-center">
                Don't have an account
                <Link to={routeConfig.signup}>&nbsp;Sign up</Link>
              </Form.Group>
              <Form.Group className="text-center">
                <Link to={routeConfig.forgotPassword}>&nbsp;Forgot Password</Link>
              </Form.Group>
            </Form>
          </div>
        </div>
      </Container>
    );
  }
}

export default Login;