import React, { Component } from 'react';
import {
  Container,
  Form,
  Button,
  Row,
  Col
} from 'react-bootstrap';

import { Link } from "react-router-dom";

import '../../styles/components/sign-up.scss';

import flashMessenger from '../../utils/flashMessenger';
import Validator from '../../utils/validator';
import api from '../../utils/api';
import config from '../../config/index';

class SignUp extends Component {
  constructor(props) {
    super(props);

    this.state = {
      formData: {
        firstName: "",
        lastName: "",
        email: "",
        password: ""
      },
      isButtonLoading: false
    };

    this.validationRules = {
      firstName: 'required|min:3',
      lastName: 'required|min:3',
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

  signUp = (e) => {
    e.preventDefault();
    let validator = new Validator(this.state.formData, this.validationRules);

    if (validator.isValid()) {
      this.setState({ isButtonLoading: true }, () => {
        api
          .post(`${config.endpoints.api}`, '/register', this.state.formData)
          .then((response) => {
            this.setState({ isButtonLoading: false });
            flashMessenger.show('success', 'Registered successfully');
            window.location.href = '/';
          }).catch((error) => {
            this.setState({ isButtonLoading: false });
            flashMessenger.show('error', error.message);
          });
      });

    } else {
      flashMessenger.show('error', validator.getErrorMessages());
    }
  }

  render() {
    const { firstName, lastName, email, password } = this.state.formData;
    const { isButtonLoading } = this.state;

    return (
      <Container>
        <div className="sign-up">
          <div className="sign-up__box">
            <div className="logo__container text-center">
              <img className="logo" src="/logo.png" alt="Logo" />
            </div>

            <Form className="sign-up__form">
              <Row>
                <Col xs={12} lg={6}>
                  <Form.Group controlId="firstName">
                    <Form.Label>First Name</Form.Label>
                    <Form.Control type="text" name="firstName" placeholder="First Name" value={firstName} onChange={this.handleChange} />
                  </Form.Group>
                </Col>
                <Col xs={12} lg={6}>
                  <Form.Group controlId="lastName">
                    <Form.Label>Last Name</Form.Label>
                    <Form.Control type="text" name="lastName" placeholder="Last Name" value={lastName} onChange={this.handleChange} />
                  </Form.Group>
                </Col>
              </Row>
              <Row>
                <Col>
                  <Form.Group controlId="email">
                    <Form.Label>Email</Form.Label>
                    <Form.Control type="email" name="email" placeholder="test@gmail.com" value={email} onChange={this.handleChange} />
                  </Form.Group>

                  <Form.Group controlId="password">
                    <Form.Label>Password</Form.Label>
                    <Form.Control type="password" name="password" placeholder="Password" value={password} onChange={this.handleChange} />
                  </Form.Group>
                  <Form.Group>
                    <Button variant="primary" type="submit" block disabled={isButtonLoading} onClick={!isButtonLoading ? this.signUp : null}>
                      {isButtonLoading ? 'Signing up...' : 'Sign Up'}
                    </Button>
                  </Form.Group>
                  <Form.Group className="text-center">
                    Already have an account
                    <Link to="/">&nbsp;Login</Link>
                  </Form.Group>
                  <Form.Group className="text-center">
                    <Link to="/forgot-password">&nbsp;Forgot Password</Link>
                  </Form.Group>
                </Col>
              </Row>
            </Form>
          </div>
        </div>
      </Container>
    );
  }
}

export default SignUp;