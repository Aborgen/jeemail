import React, { Fragment, PureComponent } from 'react';

import Content from './components/Content/Content';
import Trigger from './components/Trigger/Trigger';

class ToggleDOMNode extends PureComponent {

    render() {
        return (
            <Fragment>
                <Trigger />
                <Content />
            </Fragment>
        );
    }

}

export default ToggleDOMNode;
