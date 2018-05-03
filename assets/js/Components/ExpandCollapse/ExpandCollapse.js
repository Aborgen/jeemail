import React, { Component } from 'react';
import { findDOMNode }      from 'react-dom';
import PropTypes            from 'prop-types';

// Components
import ToggleDOMNode from '../ToggleDOMNode/ToggleDOMNode';

class ExpandCollapse extends Component {
    constructor() {
        super();
         this.state = {
             expanded: false
        };

        this.handleClick = this.handleClick.bind(this);
    }

    componentDidMount() {
        const parent = findDOMNode(this).children[0];
        parent.addEventListener('click', this.handleClick);
    }

    handleClick(e) {
        const trigger = findDOMNode(this);
        if(trigger.contains(e.target)) {
            this.setState((prevState) => {
                return {
                    expanded: !prevState.expanded
                }
            })
        }
    }

    render() {
        const { parentName, componentName, trigger, content } = this.props;
        const classOrClasses = parentName !== undefined
            ? `expandCollapseContainer ${parentName}ExpandCollapse`
            : `expandCollapseContainer`

          return (
            <div className={ classOrClasses }>
                <ToggleDOMNode context    = { "expandCollapse" }
                               parentName = { componentName }
                               trigger    = { trigger }
                               content    = { content }
                               isVisible  = { this.state.expanded } />
            </div>
        );
    }
}

export default ExpandCollapse;

ExpandCollapse.propTypes = {
    trigger: PropTypes.shape({
        body     : PropTypes.object.isRequired,
        className: PropTypes.string.isRequired
    }).isRequired,
    content: PropTypes.shape({
        body     : PropTypes.object.isRequired,
        className: PropTypes.string.isRequired
    }).isRequired,
    className: PropTypes.string.isRequired
}
