import React, { Component } from 'react';
import PropTypes            from 'prop-types';

import DropDown from '../../../DropDown/DropDown';

class AdditonalApps extends Component {

    getTrigger() {
        return (
            <span>?</span>
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
                      componentName = { "additionalApps" }
                      trigger       = { this.getTrigger() }
                      content       = { this.getContent() } />
        );
    }

}

export default AdditonalApps;

AdditonalApps.propTypes = {
    componentName: PropTypes.string.isRequired
}
