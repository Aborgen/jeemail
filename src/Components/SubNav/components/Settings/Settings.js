import React, { Component }           from 'react';

// Components
import DropDown, { Trigger, Content } from '../../../DropDown/DropDown';

class Settings extends Component {

    render() {
        return (
            <DropDown>
                <Trigger className="settings">
                    <span>&#9881;</span>
                    <div>&#9660;</div>
                </Trigger>
            </DropDown>
        );
    }

}

export default Settings;
