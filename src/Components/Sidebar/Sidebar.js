import React, { Component } from 'react';

//Components
import Button               from '../Button/Button';
import VerticalList         from '../VerticalList/VerticalList';

class SideBar extends Component {

    render() {
        return (
            <div className="sideBar">
                <div className="center">
                    <Button type={"submit"} name={"compose"} text="Compose" />
                </div>
                <VerticalList items={this.props.items} />
            </div>
        );
    }

}

export default SideBar;

SideBar.defaultProps = {
    items: [
        "Inbox",
        "Starred",
        "Important",
        "Sent Mail",
        "Drafts",
        "Personal"
    ]
};
