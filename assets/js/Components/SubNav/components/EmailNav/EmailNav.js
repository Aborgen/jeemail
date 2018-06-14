import React, { Fragment, Component } from 'react';
import { Switch, Route }              from 'react-router-dom';

import FullEmailNav from './components/FullEmailNav/FullEmailNav';
import SummaryNav   from './components/SummaryNav/SummaryNav';

class EmailNav extends Component {

    render() {
        return (
            <Switch>
                <Route exact path = "/email" component = { SummaryNav } />
                <Route path      = "/email/:defaultLabel"
                       component = { FullEmailNav } />
                <Route path      = "/email/label/:label"
                       component = { FullEmailNav } />
                <Route path      = "/email/category/:category"
                       component = { FullEmailNav } />
            </Switch>
        );
    }

}

export default EmailNav;
