import React, { Component }           from 'react';

// Components
import DropDown, { Trigger, Content } from '../../../DropDown/DropDown';

class Selection extends Component {

    render() {
        return (
            <DropDown>
                <Trigger className="selection">
                    <span><input type="checkbox"></input></span>
                    <div>&#9660;</div>
                </Trigger>
            </DropDown>
        );
    }

}

export default Selection;
