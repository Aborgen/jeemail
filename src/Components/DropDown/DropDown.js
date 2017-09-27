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
        window.addEventListener('click', this.handleClick);
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
        }

        else {
            this.showContent();
        }
        console.log(this.state.content_visible);
    }


    render() {
        const [trigger, content] = React.Children.toArray(this.props.children)
        return (
            <div className="dropdown-container">
                {trigger}
                {this.state.content_visible && content}
            </div>
        );
    }
}

export default DropDown;

export { Trigger, Content };
