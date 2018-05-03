import React, { Fragment, Component } from 'react';
import PropTypes                      from 'prop-types';

import DropDown from '../../../DropDown/DropDown';
import Button   from '../../../Button/Button';

class ProfileMenu extends Component {

    getTrigger() {
        return (
            <div>
                <img className = "centeredImg"
                     src       = { this.props.user.icon } alt = "" />
            </div>
        );
    }

    getContent() {
        const users = this.props.signedInUsers.map((user) => {
            return (
                <div className = "profile" key = {user.id}>
                    <span className = "profileIcon">
                       <img className = "centeredImg"
                            src       = { user.icon } alt = "" />
                    </span>
                    <span>
                        <div className = "profile__Name">
                            { user.name }
                        </div>
                        <div className = "profile__Email">
                            { user.email }
                        </div>
                    </span>
                </div>
            );
        });

        return (
            <Fragment>
                <div className = "profileSection profile__primary">
                    <span className = "profileIcon">
                        <img className = "centeredImg"
                             src       = {this.props.user.icon} alt = "" />
                    </span>
                    <span>
                        <div className = "profile__Name">
                            {this.props.user.name}
                        </div>
                        <div className = "profile__Email">
                            {this.props.user.email}
                        </div>
                    </span>
                </div>
                <div className = "profileSection">
                    {users}
                </div>
                <div className = "profileSection">
                    <div>
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
        id   : PropTypes.number.required,
        icon : PropTypes.string.required,
        name : PropTypes.string.required,
        email: PropTypes.string.required
    }).isRequired,
    signedInUsers: PropTypes.arrayOf(PropTypes.shape({
        id   : PropTypes.number.required,
        icon : PropTypes.string.required,
        name : PropTypes.string.required,
        email: PropTypes.string.required
    }).isRequired).isRequired
}

ProfileMenu.defaultProps = {
    user: {
            id: 0,
            icon: "https://68.media.tumblr.com/tumblr_l69l3sfEYh1qd0axho1_1280.jpg",
            name: "Gaston",
            email: "IMSOGR8@bravado.com"
          },
    signedInUsers: [
        {
            id: 1,
            icon: "http://pm1.narvii.com/6014/ac5c53a3ad7af744a0bb6ba722ff2e8836877975_00.jpg",
            name: "Groose",
            email: "WellQuiffed@bravado.com"
        },
        {
            id: 2,
            icon: "https://www.sonicstadium.org/wp-content/uploads/2015/05/271137-eggmun.jpg",
            name: "Dr. Ivo 'Eggman' Robotnik",
            email: "getaloadofthis@bravado.com"
        }
    ]
}
