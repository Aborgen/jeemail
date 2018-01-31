import React, { Component } from 'react';

// Scenes
import Home         from './Scenes/Home/index';
import SettingsMenu from './Scenes/SettingsMenu/index';
import ThemesMenu   from './Scenes/ThemesMenu/index';

class Jeemail extends Component {
    constructor() {
        super();
        this.state = {
            "currentPage": "/home"
        };
    }

    changeScene(scene) {
        this.setState((prevState) => {
            return({
                "currentPage": scene
            });
        })

    }
    render() {
        const scene = this.state.currentPage;
        let showIt;
        switch (scene) {
            case "/settings":
                showIt = <SettingsMenu changeScene={this.changeScene.bind(this)} />;
                break;

            case "/themes":
                showIt = <ThemesMenu changeScene={this.changeScene.bind(this)} />;
                break;

            case "/home":
                showIt = <Home changeScene={this.changeScene.bind(this)} />;
                break;

            default:
                showIt = <Home changeScene={this.changeScene.bind(this)} />;
                break;
        }
        return (
            showIt
        );
    }
}

export default Jeemail;
