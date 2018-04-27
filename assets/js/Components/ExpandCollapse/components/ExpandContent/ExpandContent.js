import React, { Component } from 'react';

class ExpandContent extends Component {
    render() {
        const { className } = this.props;

        return (
            <div className={`expandContent ${className}`}>
                {this.props.children}
            </div>
        );
    }
}

export default ExpandContent;
