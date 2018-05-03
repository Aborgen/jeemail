import React, { Fragment, Component } from 'react';
import PropTypes                      from 'prop-types';

import DropDown from '../../../DropDown/DropDown';

class MoreOptions extends Component {

    getTrigger() {
        return (
            <Fragment>
                <span>More</span>
                <div>&#9660;</div>
            </Fragment>
        );
    }

    getContent(){
        const links = this.props.nav.map((link) => {
            return <li key={link.name}><div>{link.name}</div></li>
        });

        return (
            <ol>
                { links }
            </ol>
        );
    }

    render() {
        return (
            <DropDown parentName    = { this.props.componentName }
                      componentName = { "moreOptions" }
                      trigger       = { this.getTrigger() }
                      content       = { this.getContent() } />
        );
    }
}

export default MoreOptions;

MoreOptions.defaultProps = {
    nav: [
        {
            name: "Mark all as read",
            url: ""
        }
    ]
}

MoreOptions.propTypes = {
    componentName: PropTypes.string.isRequired,
    nav          : PropTypes.array.isRequired
}
