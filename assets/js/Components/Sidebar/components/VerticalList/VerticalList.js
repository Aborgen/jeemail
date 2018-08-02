import React, { Component } from 'react';
import { NavLink }             from 'react-router-dom';
import PropTypes            from 'prop-types'

import Categories           from './components/Categories/Categories';
import MoreItems            from './components/MoreItems/MoreItems';

class VerticalList extends Component {

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
                <li key       = { i }
                    id        = { `sideBar${i}` }
                    className = "sideBarItem highlightable">
                    <NavLink to              = { path }
                             activeClassName = "sideBarSelected">
                             { item.name }
                    </NavLink>
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
