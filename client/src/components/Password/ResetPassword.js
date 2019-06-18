import React, { Component } from 'react';
import {
  Container,
  Form,
  Button
} from 'react-bootstrap';

import { Link } from "react-router-dom";

import '../../styles/components/change-password.scss';

import flashMessenger from '../../utils/flashMessenger';
import Validator from '../../utils/validator';
import api from '../../utils/api';
import config from '../../config/index';
import routeConfig from '../../routeConfig';
  
class ResetPassword extends Component {
  constructor(props) {
    super(props);

    this.state = {
      formData: {
        password: ""
      },
      isButtonLoading: false
    };

    this.validationRules = {
      password: 'required|min:6'
    };
  }

  handleChange = (e) => {
    let formData = { ...this.state.formData };
    formData[e.target.name] = e.target.value;
    this.setState({
      formData
    });
  }

  resetPassword = (e) => {
    e.preventDefault();

    let validator = new Validator(this.state.formData, this.validationRules);
    const { token } = this.props.match.params;
    const { password } = this.state.formData;

    const requestData = {
      password,
      token
    };

    if (validator.isValid()) {
      this.setState({ isButtonLoading: true }, () => {
        api
          .put(`${config.endpoints.api}`, '/reset-password', requestData)
          .then((response) => {
            this.setState({ isButtonLoading: false });
            flashMessenger.show('success', 'Password Reset successfully!!');
            window.location.href = routeConfig.login;
          }).catch(error => {
            this.setState({ isButtonLoading: false });
            flashMessenger.show('error', error.message)
          });
      });

    } else {
      flashMessenger.show('error', validator.getErrorMessages());
    }
  }

  render() {
    const { password } = this.state.formData;
    const { isButtonLoading } = this.state;

    return (
      <Container>
        <div className="change-password">
          <div className="change-password__box">
            <div className="logo__container text-center">
              <img className="logo" src="/logo.png" alt="Logo" />
            </div>
            <Form className="change-password__form">
              <Form.Group controlId="password">
                <Form.Label>Password</Form.Label>
                <Form.Control type="password" name="password" placeholder="Password" value={password} onChange={this.handleChange} />
              </Form.Group>
              <Form.Group>
                <Button variant="primary" type="submit" block disabled={isButtonLoading} onClick={!isButtonLoading ? this.resetPassword : null}>
                  {isButtonLoading ? 'Resetting...' : 'Reset Password'}
                </Button>
              </Form.Group>
              <Form.Group className="text-center">
                <Link to={routeConfig.login}>&nbsp;Login</Link>
              </Form.Group>
            </Form>
          </div>
        </div>
      </Container >
    );
  }
}

export default ResetPassword;