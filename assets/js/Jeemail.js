import React, { Component, Fragment } from 'react';

// Scenes
import Home from './Scenes/Home/index';

// Services
import FetchService from './Services/FetchService';

class Jeemail extends Component {
    constructor() {
        super();
        this.state = {
            "currentPage": "/home",
            "currentView": "",
            "member"     : {},
            "blocked"    : {},
            "contacts"   : {},
            "organizers" : {},
            "emails"     : {}
        };

        this.changeScene  = this.changeScene.bind(this);
        this.saveView     = this.saveView.bind(this);
        this.fetchService = new FetchService(this);
    }

    componentDidMount() {
        this.fetchService.fetch(FetchService.MEMBER);
        this.fetchService.fetch(FetchService.BLOCKED);
        this.fetchService.fetch(FetchService.CONTACTS);
        this.fetchService.fetch(FetchService.ORGANIZERS);
        this.fetchService.fetch(FetchService.EMAILS);
    }

    getView() {
        return this.state.view;
    }

    setView(view) {
        this.setState((prevState) => {
            return({ view });
        });
    }

<<<<<<< 099a6a1b10f37aa3852e294d5bac4f4d665f93ad
    saveView(view) {
        this.setState((prevState) => {
            return({
                "currentView": view
            });
        });
    }

    hasKeys(object) {
        return Object.keys(object).length > 0;
    }

    renderScene() {
        const { changeScene, saveView }    = this;
        const { currentView, currentPage, member, blocked, contacts, organizers } = this.state;
        let showIt;
        switch (currentPage) {
            case "/settings":
                showIt = <SettingsMenu
                            changeScene={changeScene}
                            currentView={currentView}
                            saveView={saveView} />;
                break;

            case "/themes":
                showIt = <ThemesMenu
                            changeScene={changeScene}
                            currentView={currentView}
                            saveView={saveView} />;
                break;

            case "/home":
            default:
                showIt = <Home
                            changeScene={changeScene}
                            currentView={currentView}
                            saveView={saveView}
                            member={member}
                            blocked={blocked}
                            contacts={contacts}
                            organizers={organizers}
                            emails={emails} />;
                break;
        }

        return showIt;
    }

    render() {
        const { member, blocked, contacts, organizers, emails } = this.state;
        const renderable = (
            this.hasKeys(member)     &&
            this.hasKeys(blocked)    &&
            this.hasKeys(contacts)   &&
            this.hasKeys(organizers) &&
            this.hasKeys(emails)
=======
    render() {
        return (
            <Home
                getView = { this.getView }
                setView = { this.setView } />
>>>>>>> Remove multi-scene scheme from React
        );
            return (
                <Fragment>
                    { renderable && this.renderScene() }
                </Fragment>
            );
    }
}

export default Jeemail;
