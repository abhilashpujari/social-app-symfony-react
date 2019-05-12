import React from 'react'
import '../styles/components/page-not-found.scss';
import {
  Container,
  Row,
  Col,
  Button
} from 'react-bootstrap';

import { Link } from "react-router-dom";

function PageNotFound() {
  return (
    <div className="error__404">
      <Container>
        <Row>
          <Col className="text-center">
            <h1 className="page__title">404</h1>
            <p className="page__description">Page your are looking for is either temporarily unavailable</p>
            <p className="page__description">or doesn't exists...</p>
          </Col>
        </Row>
        <Row>
          <Col className="text-center">
            <Link className="btn btn-primary btn-home-link" to="/">Go Home Page</Link>
          </Col>
        </Row>
      </Container>
    </div>
  );
}

export default PageNotFound;