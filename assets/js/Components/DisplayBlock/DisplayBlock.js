import React, { Fragment, Component } from 'react';
import PropTypes                      from 'prop-types';
import { Redirect, Route, Switch }    from 'react-router-dom';

import EmailBlock    from './components/EmailBlock/EmailBlock';
import SettingsBlock from './components/SettingsBlock/SettingsBlock';
import ThemesBlock   from './components/ThemesBlock/ThemesBlock';

class DisplayBlock extends Component {
    constructor() {
        super();
        this.state = {
            selectedEmails: []
        };

        this.setSelectedEmails = this.setSelectedEmails.bind(this);
    }

    setSelectedEmails(isNowSelected, index) {
        // If a row was just checked, set selectedEmails to the previous
        // state and include the new row. Otherwise, set selectedEmails to
        // include every row except the one that was unchecked.
        if(isNowSelected) {
            this.setState((prevState) => ({
                selectedEmails: [...prevState.selectedEmails, index]
            }));
        }

        else {
            this.setState((prevState) => {
                const nextState = prevState.selectedEmails.filter((email) => {
                    return email !== index;
                });
                return ({
                    selectedEmails: nextState
                });
            });
        }
    }

    render() {
        const { member, blocked, contacts, organizers, emails } = this.props;
        return (
            <Fragment>
                <Switch>
                    <Route exact path   = "/"
                                 render = {
                                     () => <Redirect to = "/email" />
                                 } />
                    <Route path   = "/email"
                           render = {
                               () => <EmailBlock emails = { emails }
                                                 selectedEmails    = { this.state.selectedEmails }
                                                 setSelectedEmails = { this.setSelectedEmails } />
                           } />
                    <Route path   = "/settings"
                           render = {
                               () => <SettingsBlock member     = { member }
                                                    blocked    = { blocked }
                                                    contacts   = { contacts }
                                                    organizers = { organizers }
                                     />
                           } />
                    <Route path   = "/theme"
                           render = {
                               () => <ThemesBlock />
                           } />
                </Switch>
            </Fragment>
        );
    }
}

export default DisplayBlock;

DisplayBlock.propTypes = {
    member: PropTypes.shape({
        address   : PropTypes.string.isRequired,
        birthday  : PropTypes.string.isRequired,
        email     : PropTypes.string.isRequired,
        first_name: PropTypes.string.isRequired,
        last_name : PropTypes.string.isRequired,
        full_name : PropTypes.string.isRequired,
        username  : PropTypes.string.isRequired,
        gender    : PropTypes.string.isRequired,
        phone     : PropTypes.string.isRequired,
        icon      : PropTypes.shape({
            icon_large : PropTypes.string.isRequired,
            icon_medium: PropTypes.string.isRequired,
            icon_small : PropTypes.string.isRequired
        }).isRequired,
        settings: PropTypes.object.isRequired,
        theme   : PropTypes.object.isRequired
    }).isRequired,
    blocked: PropTypes.arrayOf(PropTypes.shape({
        id   : PropTypes.number.isRequired,
        email: PropTypes.string.isRequired
    })).isRequired,
    contacts: PropTypes.arrayOf(PropTypes.shape({
        id          : PropTypes.number.isRequired,
        name        : PropTypes.string.isRequired,
        email       : PropTypes.string.isRequired,
        type        : PropTypes.string,
        relationship: PropTypes.string,
        nickname    : PropTypes.string,
        birthday    : PropTypes.string,
        phone       : PropTypes.string,
        job_title   : PropTypes.string,
        website     : PropTypes.string,
        notes       : PropTypes.string
    })).isRequired,
    organizers: PropTypes.shape({
        categories: PropTypes.arrayOf(PropTypes.shape({
            name      : PropTypes.string.isRequired,
            slug      : PropTypes.string.isRequired,
            visibility: PropTypes.bool.isRequired
        }).isRequired).isRequired,
        labels: PropTypes.shape({
            default: PropTypes.arrayOf(PropTypes.shape({
                name      : PropTypes.string.isRequired,
                slug      : PropTypes.string.isRequired,
                visibility: PropTypes.bool.isRequired,
            }).isRequired).isRequired,
            user: PropTypes.arrayOf(PropTypes.shape({
                name      : PropTypes.string.isRequired,
                slug      : PropTypes.string.isRequired,
                visibility: PropTypes.bool.isRequired,
            })).isRequired,
        }).isRequired
    }).isRequired,
    emails: PropTypes.objectOf(PropTypes.array).isRequired
}
