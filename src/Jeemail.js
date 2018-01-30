import React, { Component } from 'react';

// Scenes
import Home         from './Scenes/Home/index';
import SettingsMenu from './Scenes/SettingsMenu/index';

class Jeemail extends Component {
    constructor() {
        super();
        this.state = {
            "currentPage": "home"
        };
    }

    changeScene(scene) {
        const huh = scene === 'Settings' ? 'settingsMenu' : 'home';
        // console.log(huh);
        this.setState((prevState) => {
            return({
                "currentPage": huh
            });
        })

    }
    render() {
        const scene = this.state.currentPage;
        let showIt;
        switch (scene) {
            case "settingsMenu":
                showIt = <SettingsMenu changeScene={this.changeScene.bind(this)} />;
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
