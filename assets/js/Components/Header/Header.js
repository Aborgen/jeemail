import React, { Component } from 'react';

// Components
import AdditionalApps       from './components/AdditionalApps/AdditionalApps';
import Notifications        from './components/Notifications/Notifications';
import ProfileMenu          from './components/ProfileMenu/ProfileMenu';
import SearchForm           from '../../Components/SearchForm/SearchForm';

class Header extends Component {
    render() {
        const { member } = this.props;
        return (
            <div className="header">
                <div className="navPiece navLeft">
                    <div></div>
                </div>
                <div className="navPiece navRight">
                    <AdditionalApps />
                    <Notifications />
                    <ProfileMenu member = { member }/>
                </div>
                <SearchForm />
            </div>
        );
    }
}

export default Header;
