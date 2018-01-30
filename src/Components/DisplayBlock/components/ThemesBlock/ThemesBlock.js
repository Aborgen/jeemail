import React, { Component } from 'react';

// Components
import Theme from './components/Theme/Theme.js';

class ThemesBlock extends Component {
    render() {
        const themes = this.props.themes.map((theme, i) => {
            <div key={theme.id} id={`theme${i}`} className={theme.class}>{theme.title}</div>
        });
        return (
            <div className="mainBlock">
                {themes}
            </div>
        );
    }
}

export default ThemesBlock;
