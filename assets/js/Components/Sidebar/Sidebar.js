import React, { Component } from 'react';
import PropTypes            from 'prop-types';

//Components
import Button               from '../Button/Button';
import VerticalList         from './components/VerticalList/VerticalList';

class SideBar extends Component {
    constructor() {
        super();

        this.toggleHighlight = this.toggleHighlight.bind(this)
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
    }

    render() {
        return (
            <div className="sideBar">
                <div className="sideBarButton">
                    <Button type={"submit"} name={"compose"} text="Compose" />
                </div>
                <VerticalList toggleHighlight = { this.toggleHighlight }
                              organizers      = { this.props.organizers } />
            </div>
        );
    }
}

export default SideBar;

SideBar.propTypes = {
    organizers: PropTypes.shape({
        labels: PropTypes.shape({
                user: PropTypes.arrayOf(PropTypes.shape({
                    visibility: PropTypes.bool.isRequired,
                    name      : PropTypes.string.isRequired,
                    slug      : PropTypes.string.isRequired
                }).isRequired).isRequired,
                default: PropTypes.arrayOf(PropTypes.shape({
                    visibility: PropTypes.bool.isRequired,
                    name      : PropTypes.string.isRequired,
                    slug      : PropTypes.string.isRequired
                }).isRequired).isRequired
        }).isRequired,
        categories: PropTypes.arrayOf(PropTypes.shape({
            visibility: PropTypes.bool.isRequired,
            name      : PropTypes.string.isRequired,
            slug      : PropTypes.string.isRequired
        }).isRequired).isRequired
    }).isRequired
}
