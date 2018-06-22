import React, { Component, Fragment } from 'react';
import { Route, Switch }              from 'react-router-dom';

import FullEmail   from './components/FullEmail/FullEmail';
import SummaryList from './components/SummaryList/SummaryList';

class EmailBlock extends Component {

    render() {
        const { emails, selectedEmails, setSelectedEmails, fetchEmails, message } = this.props;


        return (
            <Fragment>


                <Switch>
                    <Route path   = "/email/:organizer(label|category)/:slug"
                           render = { ({ match }) => <SummaryList match             = { match }
                                                                  emails            = { emails }
                                                                  fetchEmails       = { fetchEmails }
                                                                  selectedEmails    = { selectedEmails }
                                                                  setSelectedEmails = { setSelectedEmails }
                                                                  message           = { message } /> }
                    />
                    <Route path   = "/email/:slug"
                           render = { ({ match }) => <SummaryList match             = { match }
                                                                  emails            = { emails }
                                                                  fetchEmails       = { fetchEmails }
                                                                  selectedEmails    = { selectedEmails }
                                                                  setSelectedEmails = { setSelectedEmails }
                                                                  message           = { message } /> }
                    />
                    <Route path   = "/email/:organizer(label|category)/:slug/:uid"
                           render = { ({ match }) => <FullEmail match       = { match }
                                                                emails      = { emails }
                                                                fetchEmails = { fetchEmails } /> }
                    />
                    <Route path   = "/email/:slug/:uid"
                           render = { ({ match }) => <FullEmail match       = { match }
                                                                emails      = { emails }
                                                                fetchEmails = { fetchEmails } /> }
                    />
                </Switch>
            </Fragment>
        );
    }
}

export default EmailBlock;
