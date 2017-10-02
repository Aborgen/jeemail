import React, { Component } from 'react';
import PropTypes            from 'prop-types';

class ProgressArrow extends Component {

    render() {
        const { className } = this.props;
        return (
            <span className={`progressArrow ${className}`} onClick={this.props.click}><p>{this.props.text}</p></span>
        );
    }

}

export default ProgressArrow;

ProgressArrow.propTypes = {
    text: PropTypes.string,
    click: PropTypes.func
}
