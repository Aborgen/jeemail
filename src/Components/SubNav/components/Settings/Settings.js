import React, { Component }           from 'react';

// Components
import DropDown, { Trigger, Content } from '../../../DropDown/DropDown';

class Settings extends Component {

    render() {
        const links = this.props.nav.map((link) => {
            return <li key={link.name}>{link.name}</li>
        })
        return (
            <DropDown className="subNavDropdown">
                <Trigger className="settings">
                    <span>&#9881;</span>
                    <div>&#9660;</div>
                </Trigger>
                <Content className="settings__dropdown">
                    <ol>{links}</ol>
                </Content>
            </DropDown>
        );
    }

}

export default Settings;

Settings.defaultProps = {
    nav: [
        {
            name: "Comfortable",
            url: ""
        },
        {
            name: "Cozy",
            url: ""
        },
        {
            name: "Compact",
            url: ""
        },
        {
            name: "Configure inbox",
            url: ""
        },
        {
            name: "Settings",
            url: ""
        },
        {
            name: "Themes",
            url: ""
        },
        {
            name: "Customize address",
            url: ""
        },
        {
            name: "Help",
            url: ""
        }
    ]

}
