import React, { Component } from 'react';
import { Link }             from 'react-router-dom';
import PropTypes            from 'prop-types'

import Categories           from './components/Categories/Categories';
import MoreItems            from './components/MoreItems/MoreItems';

class VerticalList extends Component {
    constructor() {
        super();

        this.handleClick = this.handleClick.bind(this);
    }

    handleClick(e) {
        const clicked = e.target.parentNode;
        const catsTrigger = document.querySelector('.categories');
        const moreTrigger = document.querySelector('.moreItems');
        if(clicked === catsTrigger || clicked === moreTrigger) {
            return;
        }

        this.props.toggleHighlight(clicked.id);
    }

    generateItems(itemGroup, rootPath = null) {
        if(!Array.isArray(itemGroup)) {
            return null;
        }

        return itemGroup.map((item, i) => {
            if(item.visiblity === false) {
                return null;
            }

            const path = rootPath
                ? `/email/${rootPath}/${item.slug}`
                : `/email/${item.slug}`;

            return (
                <li onClick   = { this.handleClick }
                    key       = { i }
                    id        = { `sideBar${i}` }
                    className = "sideBarItem highlightable">
                    <Link to = { path } >{ item.name }</Link>
                </li>
            );
        });
    }

    render() {
        const { labels, categories } = this.props.organizers;
        return (
            <ol className="verticalList">
                { this.generateItems(labels['default']) }
                <Categories componentName = "sideBar"
                            categories    = { this.generateItems(categories, 'category') } />
                <MoreItems  componentName = "sideBar"
                            userDefined   = { this.generateItems(labels['user'], 'label') } />
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
