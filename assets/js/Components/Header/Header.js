import React, { Component } from 'react';
import PropTypes            from 'prop-types';

import AdditionalApps       from './components/AdditionalApps/AdditionalApps';
import Notifications        from './components/Notifications/Notifications';
import ProfileMenu          from './components/ProfileMenu/ProfileMenu';
import Form                 from '../../Components/Form/Form';

class Header extends Component {

    getFields() {
        return [{
            name: "headerSearch",
            type: "input"
        }];
    }

    render() {
        const { member } = this.props;
        const user = {
            icon: {
                small: member.icon.icon_small,
                medium: member.icon.icon_medium
            },
            name: member.full_name,
            email: member.email
        };


        return (
            <div className="header">
                <div className="navPiece navLeft">
                    <div></div>
                </div>
                <div className="navPiece navRight">
                    <AdditionalApps componentName = "header" />
                    <Notifications  componentName = "header" />
                    <ProfileMenu    componentName = "header"
                                    user          = { user } />
                </div>
                <Form componentName = { "header" }
                      method        = { "GET" }
                      fields        = { this.getFields() }
                      buttonText    = { "❓" } />
            </div>
        );
    }
}

export default Header;

Header.propTypes = {
    member: PropTypes.shape({
        address   : PropTypes.string.isRequired,
        birthday  : PropTypes.string.isRequired,
        email     : PropTypes.string.isRequired,
        first_name: PropTypes.string.isRequired,
        last_name : PropTypes.string.isRequired,
        full_name : PropTypes.string.isRequired,
        username  : PropTypes.string.isRequired,
        gender    : PropTypes.string.isRequired,
        phone     : PropTypes.string.isRequired,
        icon      : PropTypes.shape({
            icon_large : PropTypes.string.isRequired,
            icon_medium: PropTypes.string.isRequired,
            icon_small : PropTypes.string.isRequired
        }).isRequired,
        settings: PropTypes.object.isRequired,
        theme   : PropTypes.object.isRequired
    }).isRequired
}
