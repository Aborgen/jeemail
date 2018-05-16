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
            "member": {}
        };

        this.changeScene = this.changeScene.bind(this);
        this.saveView    = this.saveView.bind(this);
    }

    componentWillMount() {
        this.setEmails();
    }

    setEmails() {
        fetch('/api/member/details', {
            method: "POST",
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then((response) => response.json())
        .then((member) => {
            this.setState({ member })
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
        const { currentView, currentPage, member } = this.state;
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
                            member={member} />;
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
