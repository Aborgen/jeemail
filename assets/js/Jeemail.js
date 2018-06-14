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
            "currentView": "",
            "message"    : Jeemail.LOADING_MESSAGE,
            "member"     : {},
            "blocked"    : {},
            "contacts"   : {},
            "organizers" : {},
            "emails"     : {}
        };

        this.fetchService = new FetchService(this);
    }

    static get LOADING_MESSAGE() { return "Loading..."; }
    static get ERROR_MESSAGE() { return "There seems to have been a problem"; }

    async componentWillMount() {
        this.checkAuthorization();
    }

    componentDidMount() {
        this.fetchService.fetch(FetchService.MEMBER);
        this.fetchService.fetch(FetchService.BLOCKED);
        this.fetchService.fetch(FetchService.CONTACTS);
        this.fetchService.fetch(FetchService.ORGANIZERS);
        this.fetchService.fetch(FetchService.EMAILS);
    }


    async checkAuthorization() {
        try {
            const response = await fetch('https://api.jeemail.com/status', {
                method: "POST",
                credentials: "include"
            });

            if(!response.ok) {
                redirect: window.location.replace('/login.php');
            }
        }
        catch (e) {
            this.setState({ message: Jeemail.ERROR_MESSAGE })
        }
    }

    render() {
        const { message, member, blocked, contacts, organizers, emails } = this.state;
        const renderable = (
            hasKeys(member)     &&
            hasKeys(blocked)    &&
            hasKeys(contacts)   &&
            hasKeys(organizers) &&
            hasKeys(emails)
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
                </Fragment> ||
                <p>{ message }</p>
                }
            </Fragment>
        );
    }
}

export default Jeemail;
