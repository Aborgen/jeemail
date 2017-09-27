import React, { Component } from 'react';

//Components
import EmailViews           from './components/EmailViews/EmailViews';
import InputTools           from './components/InputTools/InputTools';
import MoreOptions          from './components/MoreOptions/MoreOptions';
import Pages                from './components/Pages/Pages';
import Refresh              from './components/Refresh/Refresh';
import Selection            from './components/Selection/Selection';
import Settings             from './components/Settings/Settings';

class SubNav extends Component {
    render() {
        return (
            <div className="subNav">
                <div className="subNav__left">
                    <EmailViews />
                    <Selection />
                    <Refresh />
                    <MoreOptions />
                </div>
                <div className="subNav__right">
                    <Pages />
                    <InputTools />
                    <Settings />
                </div>
            </div>
        );
    }
}

export default SubNav;
