import React, { Component } from 'react';
import {
  Container,
  Form,
  Button
} from 'react-bootstrap';

import { Link } from "react-router-dom";

import logo from '../logo.png';
import '../styles/components/change-password.scss';

class ChangePassword extends Component {
  constructor(props) {
    super(props);

    this.state = {
      password: "",
      token: ""
    };
  }


  render() {
    const { password } = this.state;
    const { token } = this.props.match.params;

    return (
      <Container>
        <div className="change-password">
          <div className="change-password__box">
            <div className="logo__container text-center">
              <img className="logo" src={logo} alt="Logo" />
            </div>
            <Form className="change-password__form">
              <Form.Group controlId="password">
                <Form.Label>Password</Form.Label>
                <Form.Control type="password" placeholder="Password" value={password} onChange={(e) => this.setState({ password: e.target.value })} />
              </Form.Group>
              <Form.Group>
                <Button variant="primary" type="submit" block>
                  Change Password
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

export default ChangePassword;