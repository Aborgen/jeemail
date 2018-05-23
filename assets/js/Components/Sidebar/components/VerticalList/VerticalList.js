import React, { Component } from 'react';
import PropTypes            from 'prop-types'

import Categories           from './components/Categories/Categories';
import MoreItems            from './components/MoreItems/MoreItems';

class VerticalList extends Component {
    constructor() {
        super();
        this.handleClick = this.handleClick.bind(this);
        this.itemsLength = 0;
    }

    handleClick(e) {
        const clicked = e.target;
        const catsTrigger = document.querySelector('.categories');
        const moreTrigger = document.querySelector('.moreItems');
        if(clicked === catsTrigger || clicked === moreTrigger) {
            return;
        }

        this.props.toggleHighlight(clicked.id);
    }

    generateItem(item) {
        if(item.visiblity === false) {
            return null;
        }

        return (
            <li onClick   = { this.handleClick }
                key       = { this.itemsLength }
                id        = { `sideBar${this.itemsLength++}` }
                className = "sideBarItem highlightable">{ item.name }</li>
        );
    }

    generateItems(itemGroup) {
        if(!Array.isArray(itemGroup)) {
            return null;
        }

        return itemGroup.map((item) => this.generateItem(item));
    }

    render() {
        const { labels, categories } = this.props.organizers;
        return (
            <ol className="verticalList">
                { this.generateItems(labels['default']) }
                <Categories componentName = "sideBar"
                            categories    = { this.generateItems(categories) } />
                <MoreItems  componentName = "sideBar"
                            userDefined   = { this.generateItems(labels['user']) } />
                <li className = "sideBarItem">
                    <a href="">Edit label</a>
                </li>
            </ol>
        );
    }
}

export default VerticalList;

VerticalList.propTypes = {
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
    }).isRequired,
    toggleHighlight: PropTypes.func.isRequired
}
