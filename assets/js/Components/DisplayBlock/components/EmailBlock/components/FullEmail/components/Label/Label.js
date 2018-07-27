import React, { PureComponent } from 'react';

class Label extends PureComponent {

    render() {
        const { index, label } = this.props;
        if(!label.visibility) {
            return null;
        }

        return (
            <li id        = { `label${index}` }
                className = "label"
                title     = { `Search for all messages with the label ${ label.name }` } >
                <span>{ label.name }</span>
                <span>x</span>
            </li>
        );
    }

}

export default Label;
