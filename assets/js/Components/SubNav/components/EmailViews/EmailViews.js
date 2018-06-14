import React, { Fragment, Component } from 'react';
import { Link }                       from 'react-router-dom';
import PropTypes                      from 'prop-types';

import DropDown from '../../../DropDown/DropDown';

class EmailViews extends Component {

    getTrigger() {
        return (
            <Fragment>
                <span>EMAIL</span>
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
            );
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
                      componentName = { "emailViews" }
                      trigger       = { this.getTrigger() }
                      content       = { this.getContent() } />
        );
    }
}

export default EmailViews;

EmailViews.defaultProps = {
    nav: [
        {
            name: "Email",
            url: "/email"
        },
        {
            name: "Contacts",
            url: "/settings/contacts"
        },
        {
            name: "Tasks",
            url: ""
        }
    ]
}

EmailViews.propTypes = {
    componentName: PropTypes.string.isRequired,
    nav          : PropTypes.array.isRequired
}
