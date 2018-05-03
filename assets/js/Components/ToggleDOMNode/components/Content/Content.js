import React, { PureComponent } from 'react';
import PropTypes                from 'prop-types';

class Content extends PureComponent {

    render() {
        const { context, parentName, body } = this.props;
        const classOrClasses = parentName !== undefined
            ? `${context}Content ${parentName}Content`
            : `${context}Content`;

        return (
            <div className = { classOrClasses }>
                { body }
            </div>
        );
    }
}

export default Content;

Content.propTypes = {
    context   : PropTypes.string.isRequired,
    parentName: PropTypes.string.isRequired,
    body      : PropTypes.object.isRequired
}
