import React, { PureComponent } from 'react';

class Content extends PureComponent {

    render() {
        const { className } = this.props;

        return (
            <div className={`content ${className}`}>{this.props.children}</div>
        );
    }

}

export default DropDownContent;
