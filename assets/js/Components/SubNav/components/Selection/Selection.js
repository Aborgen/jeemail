import React, { Fragment, Component } from 'react';
import PropTypes                      from 'prop-types';

import DropDown from '../../../DropDown/DropDown';

class Selection extends Component {

    getTrigger() {
        return (
            <Fragment>
                <span><input type="checkbox"></input></span>
                <div>&#9660;</div>
            </Fragment>
        );
    }

    getContent() {
        const links = this.props.nav.map((link) => {
            return <li onClick = { this.props.selectionOpt }
                       key     = { link.name } >
                        <div>
                            { link.name }
                        </div>
                    </li>
        });

        return (
            <ol>
                {links}
            </ol>
        );
    }

    render() {
        return (
            <DropDown parentName    = { this.props.componentName }
                      componentName = { "selection" }
                      trigger       = { this.getTrigger() }
                      content       = { this.getContent() } />
        );
    }
}

export default Selection;

Selection.defaultProps = {
    nav: [
        {
            name: "All"
        },
        {
            name: "None"
        },
        {
            name: "Read"
        },
        {
            name: "Unread"
        },
        {
            name: "Starred"
        },
        {
            name: "Unstarred"
        },
    ]
}

Selection.propTypes = {
    componentName: PropTypes.string.isRequired,
    nav          : PropTypes.array.isRequired
}
