import React, { Component } from 'react';

// Components
import Trigger from './components/DropDownTrigger/DropDownTrigger'
import Content from './components/DropDownContent/DropDownContent'

class DropDown extends Component {
    /*constructor() {
        super();
         this.state = {
             content_visible: false,
             listener_active: true
        };
    }*/

    componentDidMount() {
        window.addEventListener('click', () => console.log("hey"));
    }

    // showContent() {
    //     this.setState({content_visible: !content_visible});
    // }
    //
    render() {
        return (
            <div className="dropdown-container">{this.props.children}</div>
        );
    }

}

export default DropDown;

export { Trigger, Content };
