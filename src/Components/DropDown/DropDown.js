import React, { Component } from 'react';
import { findDOMNode }      from 'react-dom';

// Components
import Content from './components/DropDownContent/DropDownContent';
import Trigger from './components/DropDownTrigger/DropDownTrigger';

class DropDown extends Component {
    constructor() {
        super();
         this.state = {
             content_visible: false
        };

        this.handleClick = this.handleClick.bind(this);
        this.hideContent = this.hideContent.bind(this);
        this.showContent = this.showContent.bind(this);
    }

    componentDidMount() {
        // TODO: Why does this need touchend event? ExpandCollapse doesn't
        //       and it is very similar.
        window.addEventListener('click', this.handleClick);
        window.addEventListener('touchend', this.handleClick);
    }

    hideContent() {
        this.setState((prevState) => {
            return {
                content_visible: false
            }
        })
    }

    showContent() {
        this.setState((prevState) => {
            return {
                content_visible: true
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
        const [trigger, content] = React.Children.toArray(this.props.children);
        const { className } = this.props;
        const classes = className !== undefined ?
            `dropdownContainer ${className}`    :
            `dropdownContainer`
        return (
            <div className={classes}>
                {trigger}
                {this.state.content_visible && content}
            </div>
        );
    }
}

export default DropDown;
export { Trigger, Content };
