import React from 'react';
import { Nav, Navbar, NavDropdown } from 'react-bootstrap';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faCog } from '@fortawesome/free-solid-svg-icons'

import identity from '../utils/identity';

const Header = () => {
  const logout = (e) => {
    e.preventDefault();
    identity.clearIdentity();
    window.location.href = '/';
  }

  if (identity.isAuthenticated()) {
    return (
      <Navbar bg="light">
        <Navbar.Brand href="/home">React-Bootstrap</Navbar.Brand>
        <Navbar.Toggle aria-controls="basic-navbar-nav" />
        <Navbar.Collapse id="basic-navbar-nav">
          <Nav className="mr-auto">
            <Nav.Link href="/home">Home</Nav.Link>
            <NavDropdown title={
              <div style={{ display: 'inline-block' }}>
                <FontAwesomeIcon icon={faCog} />
              </div>
            } id="basic-nav-dropdown">
              <NavDropdown.Item href="/account">My Account</NavDropdown.Item>
              <NavDropdown.Item onClick={(e) => { logout(e) }}>Logout</NavDropdown.Item>
            </NavDropdown>
          </Nav>
        </Navbar.Collapse>
      </Navbar>
    )
  } else {
    return (<></>);
  }
}

export default Header;