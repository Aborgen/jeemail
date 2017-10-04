import React, { Component }           from 'react';
import PropTypes                      from 'prop-types';

// Components
import DropDown, { Trigger, Content } from '../../../DropDown/DropDown';
import Button                         from '../../../Button/Button';

class ProfileMenu extends Component {

    render() {
        const users = this.props.signedInUsers.map((user) => {
            return <li key={user.id}>
                       <span className="profileIcon">
                           <img src={user.icon} alt="" />
                       </span>
                       <span>
                           <div className="profile__Name">{user.name}</div>
                           <div className="profile__Email">{user.email}</div>
                       </span>
                   </li>
        });
        return (
            <DropDown>
                <Trigger className="profileMenu">
                    <div><img src={this.props.user.icon} alt="" /></div>
                </Trigger>
                <Content className="profileMenu__dropdown">
                    <div className="profile__info">
                        <span className="profileIcon">
                            <img src={this.props.user.icon} alt="" />
                        </span>
                        <span>
                            <div className="profile__Name">{this.props.user.name}</div>
                            <div className="profile__Email">{this.props.user.email}</div>
                        </span>
                    </div>
                    <div className="profile__info">
                        <ol>
                            {users}
                        </ol>
                    </div>
                    <div className="profile__info">
                        <div>
                            <Button type="button" name="Add Account" text="Add Account" />
                            <Button type="button" name="Sign Out" text="Sign Out" />
                        </div>
                    </div>
                </Content>
            </DropDown>
        );
    }

}

export default ProfileMenu;

ProfileMenu.propTypes = {
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
    }).isRequired)
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
