import React, { Fragment, Component } from 'react';
import { Link }                       from 'react-router-dom';
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
        const links = this.props.nav.map((link, i) => {
            return (
                <li key = { i } >
                    <Link to = { link.url } >{ link.name }</Link>
                </li>
            )
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
