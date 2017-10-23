import React, { Component }           from 'react';

// Components
import DropDown, { Trigger, Content } from '../../../DropDown/DropDown';

class InputTools extends Component {

    render() {
        const links = this.props.nav.map((link) => {
            return <li key={link.id}>{link.name}</li>
        });
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
        {   id: 0,
            name: "English",
            url: ""
        },
        {   id: 1,
            name: "English Dvorak",
            url: ""
        },
        {   id: 2,
            name: "English",
            url: ""
        },
        {   id: 3,
            name: "InputTools Settings",
            url: ""
        }
    ]
}
