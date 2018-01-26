import React, { Component } from 'react';
import PropTypes            from 'prop-types';

//Components
import Button               from '../Button/Button';
import VerticalList         from './components/VerticalList/VerticalList';

class SideBar extends Component {
    constructor() {
        super();
        this.state = {
            "currentSelection": "sideBar0"
        };

        this.setSelection = this.setSelection.bind(this)
        this.expandMenu = this.expandMenu.bind(this)
    }

    componentDidMount() {
        const current = document.getElementById(this.state.currentSelection);
        current.classList.add('sideBar__currentSelection');
    }

    setSelection(selection) {
        const current = document.querySelector('.sideBar__currentSelection');
        const newNode = document.getElementById(selection);
        if(!current) {
            newNode.classList.add('sideBar__currentSelection');

        }

        if(current && current !== newNode) {
            current.classList.remove('sideBar__currentSelection');
            newNode.classList.add('sideBar__currentSelection');
        }

        this.setState((prevState) => {
            return { currentSelection: selection }
        })
    }

    expandMenu() {
        const selectedItem
            = document.getElementById(this.state.currentSelection);
        if(!selectedItem) {
            return;
        }

        selectedItem.classList.add('sideBar__currentSelection');
    }

    render() {
        return (
            <div className="sideBar">
                <div className="sideBar__button">
                    <Button type={"submit"} name={"compose"} text="Compose" />
                </div>
                <VerticalList setSelection={this.setSelection}
                              expandMenu={this.expandMenu}
                              items={this.props.items} />
            </div>
        );
    }
}

export default SideBar;
SideBar.defaultProps = {
    items: {
        "defaultItems": [
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
        "userDefined": [
            "one",
            "two",
            "hullabaloo!",
            "tan style bile kyle",
            "long long long long long long long long long"
        ]
    }
};

SideBar.propTypes = {
    items: PropTypes.shape({
        default    : PropTypes.array,
        userDefined: PropTypes.array
    })
}
