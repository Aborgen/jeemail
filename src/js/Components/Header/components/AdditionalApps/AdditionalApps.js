import React, { Component } from 'react';

// Components
import DropDown, { Trigger, Content } from '../../../DropDown/DropDown';

class AdditonalApps extends Component {

    render() {
        return (
            <DropDown className='headerDropdown'>
                <Trigger className="additionalApps">
                    <span>?</span>
                </Trigger>
                <Content></Content>
            </DropDown>
        );
    }

}

export default AdditonalApps;
