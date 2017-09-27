import React, { Component } from 'react';

// Components
import Logo                 from './components/Logo/Logo';
import NavDropdownMenu      from './components/NavDropdownMenu/NavDropdownMenu';
import SearchForm           from '../../Components/SearchForm/SearchForm';

class Header extends Component {

    render() {
        return (
            <div className="header">
                <Logo />
                <div className="navRight">
                    <div className="dropdown-group">
                        <NavDropdownMenu />
                        <NavDropdownMenu />
                        <NavDropdownMenu />
                    </div>
                </div>
                <SearchForm />
            </div>
        );
    }

}

export default Header;
