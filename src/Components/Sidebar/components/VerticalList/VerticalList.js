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

        this.props.setSelection(clicked.id);
    }

    render() {
        const defaultItems = this.props.items['default'].map((item) => {
            return <li onClick={this.handleClick}
                       key={item}
                       id={item.toLowerCase()}>{item}</li>;
        });
        const categories = this.props.items['categories'].map((item) => {
            return <li onClick={this.handleClick}
                       key={item}
                       id={item.toLowerCase()}>{item}</li>;
        });
        const userDefined = this.props.items['userDefined'].map((item) => {
            return <li onClick={this.handleClick}
                       key={item}
                       id={item.toLowerCase()}>{item}</li>;
        });

        return (
            <ol className="verticalList">
                {defaultItems}
                <Categories categories = {categories}
                            handleClick={this.handleClick} />
                <MoreItems userDefined = {userDefined}
                            handleClick={this.handleClick} />

            </ol>
        );
    }
}

export default VerticalList;

VerticalList.propTypes = {
    items: PropTypes.shape({
        default    : PropTypes.array,
        categories : PropTypes.array,
        userDefined: PropTypes.array
    })
}
