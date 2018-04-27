import React, { Component } from 'react';
import PropTypes            from 'prop-types';

class Refresh extends Component {

    render() {
        return (
            <div onClick={this.props.refreshEmails} className="refresh">
                <div className="refresh__symbol">&#8635;</div>
            </div>
        );
    }
}

export default Refresh;

Refresh.propTypes = {
    refreshEmails: PropTypes.func
}
