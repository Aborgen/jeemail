import React, { Component } from 'react';

class DropDownContent extends Component {

    render() {
        const { className } = this.props;

        return (
            <div className={`dropdownContent ${className}`}>{this.props.children}</div>
        );
    }

}

export default DropDownContent;
