import React, { Component } from 'react';

// Components
import Setting from './components/Setting/Setting.js';

class SettingsBlock extends Component {
    render() {
        return (
            <div className="mainBlock">
                <table>
                    <colgroup>
                        <col className="settingDescription" />
                        <col className="setting" />
                    </colgroup>
                    <tbody>
                        <Setting />
                        <Setting />
                        <Setting />
                        <Setting />
                        <Setting />
                    </tbody>
                </table>
            </div>
        );
    }
}

export default SettingsBlock;
