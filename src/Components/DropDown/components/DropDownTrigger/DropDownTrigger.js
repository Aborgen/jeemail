import React, { Component } from 'react';

class DropDownTrigger extends Component {

    render() {
        return (
            <div ref="dropdown-trigger" {...this.props}>{this.props.children}</div>
        );
    }

}

export default DropDownTrigger;
