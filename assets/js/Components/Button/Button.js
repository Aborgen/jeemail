import React, { Component } from 'react';
import PropTypes            from 'prop-types';

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

Button.propTypes = {
    type: PropTypes.string.isRequired,
    name: PropTypes.string.isRequired,
    text: PropTypes.string.isRequired
}
