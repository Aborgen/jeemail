import React, { Component }           from 'react';

// Components
import DropDown, { Trigger, Content } from '../../../DropDown/DropDown';

class InputTools extends Component {

    render() {
        return (
            <DropDown>
                <Trigger className="inputTools">
                    <span>
                        <a href={''}>
                            <span>&#9000;</span>
                        </a>
                        <a href={''}>
                            <span>&#9660;</span>
                        </a>
                    </span>
                </Trigger>
            </DropDown>
        );
    }

}

export default InputTools;
