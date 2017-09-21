import React, { Component } from 'react';

//Components
import Button               from '../../../../Components/Button/Button';
import VerticalList         from '../../../../Components/VerticalList/VerticalList';

class SideBar extends Component {
    constructor() {
        super();
        this.state = {
            items: [
                "Inbox",
                "Starred",
                "Important",
                "Sent Mail",
                "Drafts",
                "Personal"
            ]
        };
    }

    render() {
        return (
            <div className="sideBar">
                <div className="center">
                    <Button type={"submit"} name={"compose"} text="Compose" />
                </div>
                <VerticalList items={this.state.items} />
            </div>
        );
    }

}

export default SideBar;
