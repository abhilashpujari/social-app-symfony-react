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
      <header className="header">
        <Navbar fixed="top" bg="primary" variant="dark" expand="lg">
          <Navbar.Brand href="/home">Sociapp</Navbar.Brand>
          <Navbar.Toggle aria-controls="navbar-nav" />
          <Navbar.Collapse id="navbar-nav" className="justify-content-end">
            <Nav>
              <Nav.Link href="/home">Home</Nav.Link>
              <NavDropdown className="dropdown__setting" title={
                <span>
                  <FontAwesomeIcon icon={faCog} />
                </span>
              } id="nav-dropdown">
                <NavDropdown.Item href="/account">My Account</NavDropdown.Item>
                <NavDropdown.Item onClick={(e) => { logout(e) }}>Logout</NavDropdown.Item>
              </NavDropdown>
            </Nav>
          </Navbar.Collapse>
        </Navbar>
      </header>

    )
  } else {
    return (<></>);
  }
}

export default Header;