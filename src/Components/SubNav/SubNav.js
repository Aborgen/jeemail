import React, { Component } from 'react';
import PropTypes            from 'prop-types';

//Components
import EmailViews           from './components/EmailViews/EmailViews';
import InputTools           from './components/InputTools/InputTools';
import MoreOptions          from './components/MoreOptions/MoreOptions';
import Pages                from './components/Pages/Pages';
import Refresh              from './components/Refresh/Refresh';
import Selection            from './components/Selection/Selection';
import Settings             from './components/Settings/Settings';

// Services
// import getEmails            from '../../Services/GetEmails';

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
            <div className="subNav">
                <div className="subNav__left">
                    <EmailViews />
                    <Selection selectionOpt={this.selectionOpt.bind(this)} />
                    <Refresh refreshEmails={this.refresh.bind(this)} />
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

SubNav.propTypes = {
    refresh: PropTypes.func
}
