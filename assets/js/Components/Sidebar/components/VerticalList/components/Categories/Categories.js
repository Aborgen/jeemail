import React, { Component } from 'react';
import PropTypes            from 'prop-types';

import ExpandCollapse from '../../../../../ExpandCollapse/ExpandCollapse';

class Categories extends Component {

    getTrigger() {
        return (
            <div>
                Categories
            </div>
        );
    }

    getContent() {
        return (
            <ol>
                { this.props.categories }
            </ol>
        );
    }

    render() {
        return (
            <li className = "sideBarItem">
                <ExpandCollapse parentName = { this.props.componentName }
                             componentName = { "categories" }
                             trigger       = { this.getTrigger() }
                             content       = { this.getContent() } />
            </li>
        );
    }
}

export default Categories;

Categories.propTypes = {
    componentName: PropTypes.string.isRequired,
    categories   : PropTypes.array.isRequired
}
