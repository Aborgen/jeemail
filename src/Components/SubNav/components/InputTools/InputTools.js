import React, { Component }           from 'react';

// Components
import DropDown, { Trigger, Content } from '../../../DropDown/DropDown';

class InputTools extends Component {

    render() {
        const links = this.props.nav.map((link) => {
            return <li key={link.name}>{link.name}</li>
        })
        return (
            <div className="languageConfig">
                <div className="checky">
                    <span>&#9000;</span>
                </div>
                <DropDown>
                    <Trigger className="inputTools">
                        <span>&#9660;</span>
                    </Trigger>
                    <Content className="inputTools__dropdown">
                        <ol>{links}</ol>
                    </Content>
                </DropDown>

            </div>
        );
    }

}

export default InputTools;

InputTools.defaultProps = {
    nav: [
        {
            name: "English",
            url: ""
        },
        {
            name: "English Dvorak",
            url: ""
        },
        {
            name: "English",
            url: ""
        },
        {
            name: "InputTools Settings",
            url: ""
        }
    ]
}
