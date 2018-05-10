import React, { Component } from 'react';

// Components
import EmailBlock    from './components/EmailBlock/EmailBlock';
import SettingsBlock from './components/SettingsBlock/SettingsBlock';
import ThemesBlock   from './components/ThemesBlock/ThemesBlock';

class DisplayBlock extends Component {
    render() {
        const { blockType, emails } = this.props;
        let showIt;
        // eslint-disable-next-line
        switch (blockType) {
            case "email":
                showIt = <EmailBlock emails = { emails } />;
                break;
            case "settings":
                showIt = <SettingsBlock />;
                break;
            case "themes":
                showIt = <ThemesBlock />;
                break;
        }
        return (
            showIt
        );
    }
}

export default DisplayBlock;
