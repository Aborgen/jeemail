import React, { Component } from 'react';

class DropDownContent extends Component {

    render() {
        const { className, children} = this.props;
        console.log(children);
        return (
            <div className={`dropdownContent ${className}`}>{children}</div>
        );
    }

}

export default DropDownContent;
