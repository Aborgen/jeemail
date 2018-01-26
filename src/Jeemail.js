import React, { Component } from 'react';

// Scenes
import Home         from './Scenes/Home/index';
import SettingsMenu from './Scenes/SettingsMenu/index';

class Jeemail extends Component {
    constructor() {
        super();
        this.state = {
            "currentPage": "settingsMenu"
        };
    }

    render() {
        const scene = this.state.currentPage;
        let showIt;
        switch (scene) {
            case "settingsMenu":
                showIt = <SettingsMenu />;
                break;

            default:
                showIt = <Home />;
                break;
        }
        return (
            showIt
        );
    }
}

export default Jeemail;
