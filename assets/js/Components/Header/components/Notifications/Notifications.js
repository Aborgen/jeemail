import React, { Component } from 'react';
import PropTypes            from 'prop-types';

import DropDown from '../../../DropDown/DropDown';

class Notifications extends Component {

    getTrigger() {
        return (
            <span>!</span>
        );
    }

    getContent() {
        return (
            <span></span>
        );
    }

    render() {
        return (
            <DropDown parentName    = { this.props.componentName }
                      componentName = { "notifications" }
                      trigger       = { this.getTrigger() }
                      content       = { this.getContent() } />
        );
    }
}

export default Notifications;

Notifications.propTypes = {
    componentName: PropTypes.string.isRequired
}
