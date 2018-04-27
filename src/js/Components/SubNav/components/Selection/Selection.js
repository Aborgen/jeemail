import React, { Component }           from 'react';

// Components
import DropDown, { Trigger, Content } from '../../../DropDown/DropDown';

class Selection extends Component {

    render() {
        const links = this.props.nav.map((link) => {
            return <li
                    onClick={this.props.selectionOpt}
                    key={link.name}>
                        <div>
                            {link.name}
                        </div>
                    </li>
        });
        return (
            <DropDown className="subNavDropdown">
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
            name: "All"
        },
        {
            name: "None"
        },
        {
            name: "Read"
        },
        {
            name: "Unread"
        },
        {
            name: "Starred"
        },
        {
            name: "Unstarred"
        },
    ]
}
