import React, { Fragment, PureComponent } from 'react';
import PropTypes                          from 'prop-types'

import Content from './components/Content/Content';
import Trigger from './components/Trigger/Trigger';

class ToggleDOMNode extends PureComponent {

    render() {
        const { context, parentName, trigger, content, isVisible } = this.props;
        return (
            <Fragment>
                <Trigger context    = { context }
                         parentName = { parentName }
                         body       = { trigger } />
                { isVisible &&
                  <Content context    = { context }
                           parentName = { parentName }
                           body       = { content } />
                }
            </Fragment>
        );
    }

}

export default ToggleDOMNode;

ToggleDOMNode.propTypes = {
    context   : PropTypes.string.isRequired,
    parentName: PropTypes.string.isRequired,
    trigger   : PropTypes.object.isRequired,
    content   : PropTypes.object.isRequired,
    isVisible : PropTypes.bool.isRequired
}
