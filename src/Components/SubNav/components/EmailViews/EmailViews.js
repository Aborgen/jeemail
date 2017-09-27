import React, { Component }           from 'react';

// Components
import DropDown, { Trigger, Content } from '../../../DropDown/DropDown';

class EmailViews extends Component {

    render() {
        return (
            <DropDown>
                <Trigger className="emailViews">
                    <span>EMAIL</span>
                    <div>&#9660;</div>
                </Trigger>
                <Content className="emailViews__dropdown">
                    <div>hey</div>
                </Content>
            </DropDown>
        );
    }

}

export default EmailViews;
