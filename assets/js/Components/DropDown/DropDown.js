import React, { Component } from 'react';
import { findDOMNode }      from 'react-dom';
import PropTypes            from 'prop-types';

// Components
import ToggleDOMNode from '../ToggleDOMNode/ToggleDOMNode';

class DropDown extends Component {
    constructor() {
        super();
         this.state = {
             contentShown: false
        };

        this.handleClick = this.handleClick.bind(this);
        this.hideContent = this.hideContent.bind(this);
        this.showContent = this.showContent.bind(this);
    }

    componentDidMount() {
        // TODO: Why does this need touchend event? ExpandCollapse doesn't
        //       and it is very similar.
        const root = document.querySelector('#root');
        root.addEventListener('click', this.handleClick);
        root.addEventListener('touchend', this.handleClick);
    }

    componentWillUnmount() {
        const root = document.querySelector('#root');
        root.removeEventListener('click', this.handleClick);
        root.removeEventListener('touchend', this.handleClick);
    }

    hideContent() {
        this.setState((prevState) => {
            return {
                contentShown: false
            }
        })
    }

    showContent() {
        this.setState((prevState) => {
            return {
                contentShown: true
            }
        })
    }

    handleClick(e) {
        const dropdown = findDOMNode(this);
        if(e.target !== dropdown && !dropdown.contains(e.target)) {
            this.hideContent();
            dropdown.classList.remove('dropdown__focus');
        }

        else {
            this.showContent();
            dropdown.classList.add('dropdown__focus');
        }
    }


    render() {
        const { parentName, componentName, trigger, content } = this.props;
        const classOrClasses = parentName !== undefined
            ? `dropDownContainer ${parentName}DropDown`
            : `dropDownContainer`;

        return (
            <div className = { classOrClasses }>
                <ToggleDOMNode context    = { "dropDown" }
                               parentName = { componentName }
                               trigger    = { trigger }
                               content    = { content }
                               isVisible  = { this.state.contentShown } />
            </div>
        );
    }
}

export default DropDown;

DropDown.propTypes = {
    parentName: PropTypes.string,
    componentName: PropTypes.isRequired,
    trigger: PropTypes.object.isRequired,
    content: PropTypes.object.isRequired,
}
