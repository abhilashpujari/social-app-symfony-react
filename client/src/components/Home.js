import React, { Component } from 'react'
import {
  Container, Row, Col
} from 'react-bootstrap';

import LeftSidebar from './Sidebar/LeftSidebar';
import PostList from './Post/PostList';
import RightSidebar from './Sidebar/RightSidebar.';

class Home extends Component {
  render() {
    return (
      <Container fluid={true}>
        <Row>
          <Col xs={12} lg={3}>
            <LeftSidebar></LeftSidebar>
          </Col>

          <Col xs={12} lg={6}>
            <PostList></PostList>
          </Col>
          <Col xs={12} lg={3}>
            <RightSidebar></RightSidebar>
          </Col>
        </Row>
      </Container>
    )
  }
}

export default Home;