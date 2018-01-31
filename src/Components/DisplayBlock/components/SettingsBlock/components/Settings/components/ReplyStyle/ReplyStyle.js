import React, { Component } from 'react';

class LabelsOrText extends Component {

    render() {
        const Fragment = React.Fragment;
        return (
            <Fragment>
                <td>
                    <div>Button labels:</div>
                </td>
                <td>
                    <div>
                        <input id="labelsText0" type="radio" />
                        <label for="labelsText0">Icons</label>
                    </div>
                    <div>
                        <input id="labelsText1" type="radio" />
                        <label for="labelsText1">Text</label>
                    </div>
                </td>
            </Fragment>
        );
    }

}

export default LabelsOrText;
