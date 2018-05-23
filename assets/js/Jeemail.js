import React, { Component, Fragment } from 'react';

import DisplayBlock from './Components/DisplayBlock/DisplayBlock';
import Footer       from './Components/Footer/Footer';
import Header       from './Components/Header/Header';
import Sidebar      from './Components/Sidebar/Sidebar';
import SubNav       from './Components/SubNav/SubNav';

import FetchService from './Services/FetchService';

class Jeemail extends Component {
    constructor() {
        super();
        this.state = {
            "currentView": "",
            "member"     : {},
            "blocked"    : {},
            "contacts"   : {},
            "organizers" : {},
            "emails"     : {}
        };

        this.fetchService = new FetchService(this);
    }

    componentDidMount() {
        this.fetchService.fetch(FetchService.MEMBER);
        this.fetchService.fetch(FetchService.BLOCKED);
        this.fetchService.fetch(FetchService.CONTACTS);
        this.fetchService.fetch(FetchService.ORGANIZERS);
        this.fetchService.fetch(FetchService.EMAILS);
    }

    hasKeys(object) {
        return Object.keys(object).length > 0;
    }

    render() {
        const { member, blocked, contacts, organizers, emails } = this.state;
        const renderable = (
            this.hasKeys(member)     &&
            this.hasKeys(blocked)    &&
            this.hasKeys(contacts)   &&
            this.hasKeys(organizers) &&
            this.hasKeys(emails)
        );

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
                            emails     = { emails } />
                        <Footer />
                    </div>
                </Fragment>
                }
            </Fragment>
        );
    }
}

export default Jeemail;
