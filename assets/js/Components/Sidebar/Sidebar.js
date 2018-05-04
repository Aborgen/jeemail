import React, { Component } from 'react';
import PropTypes            from 'prop-types';

//Components
import Button               from '../Button/Button';
import VerticalList         from './components/VerticalList/VerticalList';

class SideBar extends Component {
    constructor() {
        super();
        this.state = {
            "categoriesExpaned": false,
            "moreExpanded": false
        };

        this.toggleHighlight = this.toggleHighlight.bind(this)
        this.expandMenu = this.expandMenu.bind(this)
    }

    toggleHighlight(view) {
        const current = document.querySelector('.sideBarSelected');
        const newNode = document.getElementById(view);
        if(!current) {
            newNode.classList.add('sideBarSelected');
        }

        if(current && current !== newNode) {
            current.classList.remove('sideBarSelected');
            newNode.classList.add('sideBarSelected');
        }

        this.props.setView(view);
    }

    getView() {
        const parentView = this.props.currentView;
        const thisView   = this.state.currentView;
        return parentView !== "" ? parentView : thisView
    }

    expandMenu() {
        const selectedItem = document.getElementById(this.state.currentView);
        if(!selectedItem) {
            return;
        }

        selectedItem.classList.add('sideBarSelected');
    }

    render() {
        return (
            <div className="sideBar">
                <div className="sideBarButton">
                    <Button type={"submit"} name={"compose"} text="Compose" />
                </div>
                <VerticalList toggleHighlight = { this.toggleHighlight }
                              expandMenu      = { this.expandMenu }
                              items           = { this.props.items } />
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
