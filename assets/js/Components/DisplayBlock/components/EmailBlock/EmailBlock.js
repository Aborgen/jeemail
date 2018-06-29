import React, { Component, Fragment } from 'react';
import { Route, Switch }              from 'react-router-dom';

import FullEmail   from './components/FullEmail/FullEmail';
import SummaryList from './components/SummaryList/SummaryList';

import checkEmailState from '../../../../Services/checkEmailState';

class EmailBlock extends Component {

    render() {
        const { emails, selectedEmails, setSelectedEmails,
                fetchEmails, message } = this.props;
        const checkState = checkEmailState(fetchEmails);

        return (
            <Fragment>


                <Switch>
                    <Route exact path   = "/email/:organizer(label|category)/:slug"
                           render = { ({ match }) => <SummaryList match             = { match }
                                                                  emails            = { emails }
                                                                  selectedEmails    = { selectedEmails }
                                                                  setSelectedEmails = { setSelectedEmails }
                                                                  message           = { message }
                                                                  checkState        = { checkState } /> }
                    />
                    <Route exact path   = "/email/:slug"
                           render = { ({ match }) => <SummaryList match             = { match }
                                                                  emails            = { emails }
                                                                  selectedEmails    = { selectedEmails }
                                                                  setSelectedEmails = { setSelectedEmails }
                                                                  message           = { message }
                                                                  checkState        = { checkState } /> }
                    />
                    <Route path   = "/email/:organizer(label|category)/:slug/:uid"
                           render = { ({ match }) => <FullEmail match          = { match }
                                                                emails         = { emails }
                                                                message        = { message }
                                                                componentName  = { "emailBlock" }
                                                                checkState     = { checkState } /> }
                    />
                    <Route path   = "/email/:slug/:uid"
                           render = { ({ match }) => <FullEmail match          = { match }
                                                                emails         = { emails }
                                                                message        = { message }
                                                                componentName  = { "emailBlock" }
                                                                checkState     = { checkState } /> }
                    />
                </Switch>
            </Fragment>
        );
    }
}

export default EmailBlock;
