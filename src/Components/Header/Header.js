import React, { Component }           from 'react';

// Components
import AdditionalApps                 from './components/AdditionalApps/AdditionalApps';
import Logo                           from './components/Logo/Logo';
import Notifications                  from './components/Notifications/Notifications';
import ProfileMenu                    from './components/ProfileMenu/ProfileMenu';
import SearchForm                     from '../../Components/SearchForm/SearchForm';

class Header extends Component {

    render() {
        return (
            <div className="header">
                <Logo />
                <div className="navRight">
                    <div className="dropdown-group">
                        <AdditionalApps />
                        <Notifications />
                        <ProfileMenu />
                    </div>
                </div>
                <SearchForm />
            </div>
        );
    }

}

export default Header;
