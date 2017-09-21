import React, { Component } from 'react';

class Button extends Component {

    render() {
        return (
            <button className="btn" type={this.props.type} name={this.props.name}>
                {this.props.text}
            </button>
        );
    }

}

export default Button;
