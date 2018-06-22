import React, { Component, Fragment } from 'react';

import DisplayBlock from './Components/DisplayBlock/DisplayBlock';
import Footer       from './Components/Footer/Footer';
import Header       from './Components/Header/Header';
import Sidebar      from './Components/Sidebar/Sidebar';
import SubNav       from './Components/SubNav/SubNav';

import FetchService from './Services/FetchService';
import hasKeys      from './Services/hasKeys';

class Jeemail extends Component {
    constructor() {
        super();
        this.state = {
            "authorized" : false,
            "currentView": "",
            "message"    : "",
            "member"     : {},
            "blocked"    : {},
            "contacts"   : {},
            "organizers" : {},
            "emails"     : {}
        };

        this.fetchService = new FetchService(this);
    }

    componentWillMount() {
        this.fetchService.checkAuthorization();
    }

    componentDidMount() {
        this.getFromApi();
    }

    componentDidUpdate(prevProps, prevState) {
        if(this.state.authorized && !prevState.authorized) {
            this.getFromApi();
        }
    }

    getFromApi() {
        if(this.state.authorized) {
            this.fetchService.fetch(FetchService.MEMBER)();
            this.fetchService.fetch(FetchService.BLOCKED)();
            this.fetchService.fetch(FetchService.CONTACTS)();
            this.fetchService.fetch(FetchService.ORGANIZERS)();
        }
    }

    render() {
        const { message, member, blocked, contacts, organizers, emails } = this.state;
        const renderable = (
            hasKeys(member)     &&
            hasKeys(blocked)    &&
            hasKeys(contacts)   &&
            hasKeys(organizers)
        );
        const fetchEmails = this.state.authorized && this.fetchService.fetch(FetchService.EMAILS);

        return (
            <Fragment>
                { renderable &&
                <Fragment>
                    <Header member = { member } />
                    <SubNav />
                    <Sidebar organizers = { organizers } />
                    <div className="displayContainer">
                        <DisplayBlock
                            blockType  = {"email"}
                            member     = { member }
                            blocked    = { blocked }
                            contacts   = { contacts }
                            organizers = { organizers }
                            emails     = { emails }
                            fetchEmails = { fetchEmails }
                            message = { message } />
                        <Footer />
                    </div>
                </Fragment>  ||
                <p>{ message }</p>
                }
            </Fragment>
        );
    }
}

export default Jeemail;
