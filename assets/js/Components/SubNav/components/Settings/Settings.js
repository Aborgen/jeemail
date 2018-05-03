import React, { Fragment, Component } from 'react';
import PropTypes                      from 'prop-types';

import DropDown from '../../../DropDown/DropDown';

class Settings extends Component {

    getTrigger() {
        return (
            <Fragment>
                <span>&#9881;</span>
                <div>&#9660;</div>
            </Fragment>
        );
    }

    getContent() {
        const links = this.props.nav.map((link) => {
            return <li key = { link.name }
                       id  = { link.url }>
                    { link.name }
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
                      componentName = { "settings" }
                      trigger       = { this.getTrigger() }
                      content       = { this.getContent() } />
        );
    }
}

export default Settings;

Settings.defaultProps = {
    nav: [
        {
            name: "Comfortable",
            url: ""
        },
        {
            name: "Cozy",
            url: ""
        },
        {
            name: "Compact",
            url: ""
        },
        {
            name: "Configure inbox",
            url: ""
        },
        {
            name: "Settings",
            url: "/settings"
        },
        {
            name: "Themes",
            url: "/themes"
        },
        {
            name: "Customize address",
            url: ""
        },
        {
            name: "Help",
            url: ""
        }
    ]
}

Settings.propTypes = {
    componentName: PropTypes.string.isRequired,
    nav          : PropTypes.array.isRequired
}
