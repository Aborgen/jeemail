import React, { PureComponent } from 'react';
import PropTypes                from 'prop-types';

class Trigger extends PureComponent {

    render() {
        const { context, parentName, body } = this.props;
        const classOrClasses = parentName !== undefined
            ? `${context}Trigger ${parentName}Trigger`
            : `${context}Trigger`;

        return (
            <div className = { classOrClasses }>
                { body }
            </div>
        );
    }
}

export default Trigger;

Trigger.propTypes = {
    context   : PropTypes.string.isRequired,
    parentName: PropTypes.string.isRequired,
    body      : PropTypes.object.isRequired
}
