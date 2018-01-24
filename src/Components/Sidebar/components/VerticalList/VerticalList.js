import React, { Component } from 'react';
import PropTypes            from 'prop-types'

// Components
import ExpandCollapse,
    { Content,Trigger } from '../../../ExpandCollapse/ExpandCollapse';

class VerticalList extends Component {
    handleClick(e) {
        const clicked = e.target;
        this.props.setSelection(clicked);
    }

    render() {
        const defaultItems = this.props.items['default'].map((item) => {
            return <li onClick={this.handleClick.bind(this)}
                       key={item}
                       id={item.toLowerCase()}>{item}</li>;
        });
        const categories = this.props.items['categories'].map((item) => {
            return <li onClick={this.handleClick.bind(this)}
                       key={item}
                       id={item.toLowerCase()}>{item}</li>;
        });
        const firstCategory = categories.shift();

        return (
            <ol className="verticalList">
                {defaultItems}
                <ExpandCollapse>
                    <Trigger>
                        {firstCategory}
                    </Trigger>
                    <Content>
                        {categories}
                    </Content>
                </ExpandCollapse>
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
