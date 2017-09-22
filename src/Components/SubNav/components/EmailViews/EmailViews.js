import React, { Component }           from 'react';

// Components
import DropDown, { Trigger, Content } from '../../../DropDown/DropDown';

class EmailViews extends Component {

    render() {
        return (
            <DropDown>
                <Trigger className="emailViews">
                    <span>JEEMAIL</span>
                    <div>&#9660;</div>
                </Trigger>
            </DropDown>
        );
    }

}

export default EmailViews;
