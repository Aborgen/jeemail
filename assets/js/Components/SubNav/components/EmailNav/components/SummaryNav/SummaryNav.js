import React, { Fragment, PureComponent } from 'react';

import Selection   from '../../../Selection/Selection';
import Refresh     from '../../../Refresh/Refresh';
import MoreOptions from '../../../MoreOptions/MoreOptions';

class SummaryNav extends PureComponent {

    render() {
        return (
            <Fragment>
                <Selection   componentName = "subNav" />
                <Refresh     componentName = "subNav" />
                <MoreOptions componentName = "subNav" />
            </Fragment>
        );
    }
}

export default SummaryNav;
