import React, { PureComponent } from 'react';

class Trigger extends PureComponent {

    render() {
        return (
            <div ref="trigger" {...this.props}></div>

        );
    }
}

export default Trigger;
