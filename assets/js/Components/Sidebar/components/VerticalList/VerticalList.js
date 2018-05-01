import React, { Component } from 'react';
import PropTypes            from 'prop-types'

// components
import Categories           from './components/Categories/Categories';
import MoreItems            from './components/MoreItems/MoreItems';

class VerticalList extends Component {
    constructor() {
        super();
        this.handleClick = this.handleClick.bind(this);
    }

    handleClick(e) {
        const clicked = e.target;
        const catsTrigger = document.querySelector('.categories');
        const moreTrigger = document.querySelector('.moreItems');
        if(clicked === catsTrigger || clicked === moreTrigger) {
            this.props.expandMenu();
            return;
        }

        this.props.toggleHighlight(clicked.id);
    }

    render() {
        let items = {};
        let len   = 0;
        for(const group in this.props.items) {
            // (no functions declared inside loop)
            // eslint-disable-next-line
            const domNodeGroup = this.props.items[group].map((item, i) => {
                return <li onClick={this.handleClick}
                           key={item}
                           id={`sideBar${i + len}`}
                           className="sideBar__item">{item}</li>;
            });
            items[group] = domNodeGroup;
            len += domNodeGroup.length;
        }

        return (
            <ol className="verticalList">
                {items['defaultItems']}
                <Categories categories = {items['categories']}
                            handleClick={this.handleClick} />
                <MoreItems userDefined = {items['userDefined']}
                            handleClick={this.handleClick} />
                <li>
                    <a href="">Edit label</a>
                </li>
            </ol>
        );
    }
}

export default VerticalList;

VerticalList.propTypes = {
    items: PropTypes.shape({
        defaultItems : PropTypes.array,
        categories   : PropTypes.array,
        userDefined  : PropTypes.array
    })
}
