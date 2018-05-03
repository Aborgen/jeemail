import React, { Component } from 'react';
import PropTypes            from 'prop-types';

import EmailViews           from './components/EmailViews/EmailViews';
import InputTools           from './components/InputTools/InputTools';
import MoreOptions          from './components/MoreOptions/MoreOptions';
import Pages                from './components/Pages/Pages';
import Refresh              from './components/Refresh/Refresh';
import Selection            from './components/Selection/Selection';
import Settings             from './components/Settings/Settings';

class SubNav extends Component {

    refresh() {
        // const beginning = Math.random() * (100 - 0) + 0,
        //       end       = Math.random() * (100 - 0) + 0;
        this.props.refresh(/*getEmails(beginning, end)*/);
    }


    selectionOpt(e) {
        this.props.selectionOpt(e.target.innerText);
    }

    render() {
        return (
            <div className = "subNav">
                <div className = "subNavPiece subNavLeft">
                    <EmailViews  componentName = "subNav" />
                    <Selection   componentName = "subNav" />
                    <Refresh     componentName = "subNav" />
                    <MoreOptions componentName = "subNav" />
                </div>
                <div className = "subNavPiece subNavRight">
                    <Pages       componentName = "subNav" />
                    <InputTools  componentName = "subNav" />
                    <Settings    componentName = "subNav" />
                </div>
            </div>
        );
    }
}

export default SubNav;

SubNav.propTypes = {
    refresh: PropTypes.func
}
