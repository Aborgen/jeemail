import React, { Component }           from 'react';

// Components
import DropDown, { Trigger, Content } from '../../../DropDown/DropDown';

class MoreOptions extends Component {

    render() {
        return (
            <DropDown>
                <Trigger className="moreOptions">
                    <span>More</span>
                    <div>&#9660;</div>
                </Trigger>
            </DropDown>
        );
    }

}

export default MoreOptions;
