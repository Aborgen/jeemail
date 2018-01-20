import React, { Component }           from 'react';

// Components
import DropDown, { Trigger, Content } from '../../../DropDown/DropDown';

class EmailViews extends Component {

    render() {
        const links = this.props.nav.map((link) => {
            return <li key={link.name}><div>{link.name}</div></li>
        });
        return (
            <DropDown className="subNavDropdown">
                <Trigger className="emailViews">
                    <span>EMAIL</span>
                    <div>&#9660;</div>
                </Trigger>
                <Content className="emailViews__dropdown">
                    <ol>
                        {links}
                    </ol>
                </Content>
            </DropDown>
        );
    }

}

export default EmailViews;

EmailViews.defaultProps = {
    nav: [
        {
            name: "Email",
            url: ""
        },
        {
            name: "Contacts",
            url: ""
        },
        {
            name: "Tasks",
            url: ""
        }
    ]
}
