import React, { Component } from 'react';

// Components
import DropDown, { Trigger, Content } from '../../../DropDown/DropDown';

class Notifications extends Component {

    render() {
        return (
            <DropDown className='headerDropdown'>
                <Trigger className="notifications">
                    <span>!</span>
                </Trigger>
                <Content></Content>
            </DropDown>
        );
    }

}

export default Notifications;
