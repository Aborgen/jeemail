import React, { Component } from 'react';

class DropDownContent extends Component {

    render() {
        return (
            <div ref="dropdown-content" {...this.props}></div>
        );
    }

}

export default DropDownContent;
