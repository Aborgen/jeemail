import React, { Component } from 'react';

// Components
import ExpandCollapse,
    { Content,Trigger } from '../../../../../ExpandCollapse/ExpandCollapse';

class MoreItems extends Component {
    render() {
        return (
            <li>
                <ExpandCollapse>
                    <Trigger>
                        <div onClick={this.props.handleClick} className="moreItems">More</div>
                    </Trigger>
                    <Content>
                        {this.props.userDefined}
                    </Content>
                </ExpandCollapse>
            </li>
        );
    }
}

export default MoreItems;
