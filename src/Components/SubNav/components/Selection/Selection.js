import React, { Component }           from 'react';

// Components
import DropDown, { Trigger, Content } from '../../../DropDown/DropDown';

class Selection extends Component {

    render() {
        const links = this.props.nav.map((link) => {
            return <li key={link.name}><div>{link.name}</div></li>
        });
        return (
            <DropDown>
                <Trigger className="selection">
                    <span><input type="checkbox"></input></span>
                    <div>&#9660;</div>
                </Trigger>
                <Content className="selection__dropdown">
                    <ol>
                        {links}
                    </ol>
                </Content>
            </DropDown>
        );
    }

}

export default Selection;

Selection.defaultProps = {
    nav: [
        {
            name: "All",
            url: ""
        },
        {
            name: "None",
            url: ""
        },
        {
            name: "Read",
            url: ""
        },
        {
            name: "Unread",
            url: ""
        },
        {
            name: "Starred",
            url: ""
        },
        {
            name: "Unstarred",
            url: ""
        },
    ]
}
