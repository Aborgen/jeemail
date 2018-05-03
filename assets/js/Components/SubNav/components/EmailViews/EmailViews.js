import React, { Fragment, Component } from 'react';
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
        const links = this.props.nav.map((link) => {
            return <li key={link.name}><div>{link.name}</div></li>
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
            url: ""
        },
        {
            name: "Contacts",
            url: ""
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
