import React, { Component } from 'react';

// Scenes
import Home         from './Scenes/Home/index';
import SettingsMenu from './Scenes/SettingsMenu/index';
import ThemesMenu   from './Scenes/ThemesMenu/index';

class Jeemail extends Component {
    constructor() {
        super();
        this.state = {
            "currentPage": "/home",
            "currentView": "",
            "emails": []
        };

        this.changeScene = this.changeScene.bind(this);
        this.saveView    = this.saveView.bind(this);

        this.setEmails();
    }

    setEmails() {
        fetch('/details', {
            method: "POST",
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then((response) => response.json())
        .then((emails) => {
            this.setState({ emails })
            console.log(emails);
        });
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
        const { currentView, currentPage } = this.state;
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
                            saveView={saveView} />;
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
