import React, { Component } from 'react';
import PropTypes            from 'prop-types';

//Components
import Button               from '../Button/Button';
import VerticalList         from './components/VerticalList/VerticalList';

class SideBar extends Component {

    render() {
        return (
            <div className="sideBar">
                <div className="sideBarButton">
                    <Button type={"submit"} name={"compose"} text="Compose" />
                </div>
                <VerticalList organizers = { this.props.organizers } />
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
