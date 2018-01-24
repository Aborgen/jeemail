import React, { Component } from 'react';
import PropTypes            from 'prop-types';

//Components
import Button               from '../Button/Button';
import VerticalList         from './components/VerticalList/VerticalList';

class SideBar extends Component {
    constructor() {
        super();
        this.state = {
            "currentSelection": "inbox"
        };

        this.setSelection = this.setSelection.bind(this)
    }

    componentDidMount() {
        const current = document.getElementById(this.state.currentSelection);
        current.classList.add('sideBar__currentSelection');
    }

    setSelection(selection) {
        // TODO: Ensure that 'Categories' trigger cannot be highlighted.
        //       Ensure that Sidebar remembers to highlight nodes that
        //       are hidden with Ex[andCollapse!
        const current = document.querySelector('.sideBar__currentSelection');
        if(current === null) {
            selection.classList.add('sideBar__currentSelection');
        }

        if(selection !== current && current !== null) {
            current.classList.remove('sideBar__currentSelection');
            selection.classList.add('sideBar__currentSelection');
        }
    }

    render() {
        return (
            <div className="sideBar">
                <div className="sideBar__button">
                    <Button type={"submit"} name={"compose"} text="Compose" />
                </div>
                <VerticalList setSelection={this.setSelection}
                              items={this.props.items} />
            </div>
        );
    }
}

export default SideBar;
SideBar.defaultProps = {
    items: {
        "default": [
            "Inbox",
            "Starred",
            "Important",
            "Sent Mail",
            "Drafts",
            "Personal"
        ],
        "categories": [
            "Social",
            "Promotions",
            "Updates",
            "Forums"
        ],
        "userDefined": []
    }
};

SideBar.propTypes = {
    items: PropTypes.shape({
        default    : PropTypes.array,
        userDefined: PropTypes.array
    })
}
