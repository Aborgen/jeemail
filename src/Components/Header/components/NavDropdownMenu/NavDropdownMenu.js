import React, { Component } from 'react';

class NavDropdownMenu extends Component {

    render() {
        let fresh = {
            fontSize: "2rem",
            verticalAlign: "middle",
            textAlign: "center"
        };

        return (
            <div className="dropdown">
                <div>
                    <span style={fresh} role="img">🍔</span>
                </div>
            </div>
        );
    }

}

export default NavDropdownMenu;
