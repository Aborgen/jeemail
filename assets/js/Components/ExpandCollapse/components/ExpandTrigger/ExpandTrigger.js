import React, { Component } from 'react';

class ExpandTrigger extends Component {
    render() {
        const { className } = this.props;

        return (
            <div className={`expandTrigger ${className}`}>
                {this.props.children}
            </div>
        );
    }
}

export default ExpandTrigger;
