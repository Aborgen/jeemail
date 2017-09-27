import React, { Component }           from 'react';

// Components
import DropDown, { Trigger, Content } from '../../../DropDown/DropDown';

class MoreOptions extends Component {

    render() {
        const links = this.props.nav.map((link) => {
            return <li key={link.name}><div>{link.name}</div></li>
        });
        return (
            <DropDown>
                <Trigger className="moreOptions">
                    <span>More</span>
                    <div>&#9660;</div>
                </Trigger>
                <Content className="moreOptions__dropdown">
                    <ol>
                        {links}
                    </ol>
                </Content>
            </DropDown>
        );
    }

}

export default MoreOptions;

MoreOptions.defaultProps = {
    nav: [
        {
            name: "Mark all as read",
            url: ""
        }
    ]
}
