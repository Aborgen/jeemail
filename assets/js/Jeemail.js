import React, { Component, Fragment } from 'react';

// Scenes
import Home         from './Scenes/Home/index';
import SettingsMenu from './Scenes/SettingsMenu/index';
import ThemesMenu   from './Scenes/ThemesMenu/index';

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
            "organizers" : {}
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
    }

    shouldComponentUpdate(nextProps, nextState) {
        return this.currentPage === nextState.currentPage ? false : true;
    }

    changeScene(scene) {
        this.setState((prevState) => {
            return({
                "currentPage": scene
            });
        });
    }

    saveView(view) {
        this.setState((prevState) => {
            return({
                "currentView": view
            });
        });
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
                            organizers={organizers} />;
                break;
        }

        return showIt;
    }

    render() {
        const scene = this.renderScene();
        return (
            scene
        );
    }
}

export default Jeemail;
