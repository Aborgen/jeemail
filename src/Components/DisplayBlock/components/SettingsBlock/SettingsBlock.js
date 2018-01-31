import React, { Component } from 'react';

// Components
import Settings from './components/Settings/Settings.js';

class SettingsBlock extends Component {
    render() {
        const { radio, selection } = this.props.settings;

        return (
            <div className="mainBlock">
                <table>
                    <colgroup>
                        <col className="settingDescription" />
                        <col className="setting" />
                    </colgroup>
                    <tbody>
                        <Settings type="radio" settings={radio} />
                        <Settings type="selection" settings={selection} />
                    </tbody>
                </table>
            </div>
        );
    }
}

export default SettingsBlock;

SettingsBlock.defaultProps = {
    "settings" : {
        "radio": [
            {
                "name": "Button labels:",
                "id": "labelsText",
                "data": [
                    "Icons",
                    "Text"
                ]
            },
            {
                "name": "Default reply behavior:",
                "id": "replyStyle",
                "data": [
                    "Reply",
                    "Reply all"
                ]
            },
            {
                "name": "Images:",
                "id": "displayImg",
                "data": [
                    "Always display external images",
                    "Ask before displaying external images"
                ]
            }
        ],
        "selection": [
            {
                "name": "Maximum page size:",
                "id": "pageSize",
                "data": [
                    {
                        "before": "Show",
                        "after": "conversations per page",
                        "enum": ["10", "15", "25", "50", "100"]
                    },
                    {
                        "before": "Show",
                        "after": "contacts per page",
                        "enum": ["50", "100", "250"]
                    }
                ]
            }
        ]
    }
}
