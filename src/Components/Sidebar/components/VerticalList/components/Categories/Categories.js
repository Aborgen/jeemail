import React, { Component } from 'react';

// Components
import ExpandCollapse,
    { Content,Trigger } from '../../../../../ExpandCollapse/ExpandCollapse';

class Categories extends Component {
    render() {
        return (
            <li>
                <ExpandCollapse>
                    <Trigger>
                        <div onClick={this.props.handleClick} className="categories">Categories</div>
                    </Trigger>
                    <Content>
                        {this.props.categories}
                    </Content>
                </ExpandCollapse>
            </li>
        );
    }
}

export default Categories;
