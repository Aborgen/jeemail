import React, { Component } from 'react';
import { findDOMNode }      from 'react-dom';

// Components
import Content from './components/ExpandContent/ExpandContent';
import Trigger from './components/ExpandTrigger/ExpandTrigger';

class ExpandCollapse extends Component {
    constructor() {
        super();
         this.state = {
             expanded: false
        };

        this.handleClick = this.handleClick.bind(this);
    }

    componentDidMount() {
        const parent = document.querySelector('.expandCollapseContainer');
        parent.addEventListener('click', this.handleClick);
        parent.addEventListener('touchend', this.handleClick);
    }

    handleClick(e) {
        const [trigger] = findDOMNode(this).children;
        console.log(e.target);
        console.log(trigger);
        if(e.target === trigger || trigger.contains(e.target)) {
            this.setState((prevState) => {
                return {
                    expanded: !prevState.expanded
                }
            })
        }
    }

    render() {
        const [trigger, content] = React.Children.toArray(this.props.children);
        const { className } = this.props;
        const classes = className !== undefined   ?
            `expandCollapseContainer ${className}`:
            `expandCollapseContainer`
          return (
            <div className={classes}>
                {trigger}
                {this.state.expanded && content}
            </div>
        );
    }
}

export default ExpandCollapse;
export { Trigger, Content };
