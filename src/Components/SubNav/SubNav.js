import React, { Component } from 'react';

//Components
import Selection            from './components/Selection/Selection';
import MoreOptions          from './components/MoreOptions/MoreOptions';
import EmailViews           from './components/EmailViews/EmailViews';
import InputTools           from './components/InputTools/InputTools';
import Settings             from './components/Settings/Settings';

class SubNav extends Component {
    render() {
        return (
            <div className="subNav">
                <div className="subNav__left">
                    <EmailViews />
                    <Selection />
                    <div className="refresh">
                        <div>&#8635;</div>
                    </div>
                    <MoreOptions />
                </div>
                <div className="subNav__right">
                    <div>
                        <div className="pageSort">
                            <span>
                                <span>1</span>
                                --
                                <span>100</span>
                                of
                                <span>100</span>
                            </span>
                        </div>
                        <div className="pagiation">
                            <span>&#60;</span>
                        </div>
                        <div>
                            <span>&#62;</span>
                        </div>
                    </div>
                    <InputTools />
                    <Settings />
                </div>
            </div>
        );
    }
}

export default SubNav;
