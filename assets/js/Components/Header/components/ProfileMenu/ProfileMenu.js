import React, { Fragment, Component } from 'react';
import PropTypes                      from 'prop-types';

import Button   from '../../../Button/Button';
import DropDown from '../../../DropDown/DropDown';
import Profile  from './components/Profile/Profile';

class ProfileMenu extends Component {

    getTrigger() {
        //<img className = "centeredImg"
        //     src       = { this.props.user.icon } alt = "" />
        return (
            <span className = "profileIcon iconSmall">
                <img src   = { this.props.user.icon.small }
                     title = {`${ this.props.user.name }'s profile icon`} />
            </span>
        );
    }

    getContent() {
        const { signedInUsers, user } = this.props;
        const users = signedInUsers.map((user, index) => {
            return (
                <Profile key     = { index }
                         imgSize = { "small" }
                         user    = { user } />
            );
        });

        return (
            <Fragment>
                <div className = "profileSection profilePrimary">
                    <Profile imgSize = { "medium" } user = { user } />
                </div>
                <div className = "profileSection profileAdditional">
                    { users }
                </div>
                <div className = "profileSection">
                    <div className = "profileActions" >
                        <Button type = "button"
                                name = "Add Account"
                                text = "Add Account" />
                        <Button type = "button"
                                name = "Sign Out"
                                text = "Sign Out" />
                    </div>
                </div>
            </Fragment>
        );
    }

    render() {
        return (
            <DropDown parentName    = { this.props.componentName }
                      componentName = { "profileMenu" }
                      trigger       = { this.getTrigger() }
                      content       = { this.getContent() } />
        );
    }
}

export default ProfileMenu;

ProfileMenu.propTypes = {
    componentName: PropTypes.string.isRequired,
    user: PropTypes.shape({
        icon: PropTypes.shape({
            small : PropTypes.string.isRequired,
            medium: PropTypes.string.isRequired
        }).isRequired,
        name : PropTypes.string.required,
        email: PropTypes.string.required
    }).isRequired,
    signedInUsers: PropTypes.arrayOf(PropTypes.shape({
        icon: PropTypes.shape({
            small : PropTypes.string.isRequired,
            medium: PropTypes.string.isRequired
        }).isRequired,
        name : PropTypes.string.required,
        email: PropTypes.string.required
    }).isRequired).isRequired
}

ProfileMenu.defaultProps = {
    user: {
            icon: {
                small: "https://68.media.tumblr.com/tumblr_l69l3sfEYh1qd0axho1_1280.jpg",
                medium: ""
            },
            name: "Gaston",
            email: "IMSOGR8@bravado.com"
    },
    signedInUsers: [
        {
            icon: {
                small: "http://pm1.narvii.com/6014/ac5c53a3ad7af744a0bb6ba722ff2e8836877975_00.jpg",
                medium: ""
            },
            name: "Groose",
            email: "WellQuiffed@bravado.com"
        },
        {
            icon: {
                small: "https://www.sonicstadium.org/wp-content/uploads/2015/05/271137-eggmun.jpg",
                medium: ""
            },
            name: "Dr. Ivo 'Eggman' Robotnik",
            email: "getaloadofthis@bravado.com"
        }
    ]
}
