import React, { Component } from 'react';
import PropTypes            from 'prop-types';

import ExpandCollapse from '../../../../../ExpandCollapse/ExpandCollapse';

class MoreItems extends Component {

    getTrigger() {
        return (
            <div>
                More
            </div>
        );
    }

    getContent() {
        return (
            <ol>
                { this.props.userDefined }
            </ol>
        );
    }

    render() {
        return (
            <li className = "sideBarItem">
                <ExpandCollapse parentName = { this.props.componentName }
                             componentName = { "moreItems" }
                             trigger       = { this.getTrigger() }
                             content       = { this.getContent() } />
            </li>
        );
    }
}

export default MoreItems;

MoreItems.propTypes = {
    componentName: PropTypes.string.isRequired,
    userDefined  : PropTypes.array.isRequired
}
