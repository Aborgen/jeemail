import React, { Component } from 'react';

class DisplayImages extends Component {

    render() {
        const Fragment = React.Fragment;
        return (
            <Fragment>
                <td>
                    <div>Images:</div>
                </td>
                <td>
                    <div>
                        <input id="displayImg" type="radio" />
                        <label for="displayImg">
                            Always display external images
                        </label>
                    </div>
                    <div>
                        <input id="displayImg" type="radio" />
                        <label for="displayImg">
                            Ask before displaying external images
                        </label>
                    </div>
                </td>
            </Fragment>
        );
    }

}

export default DisplayImages;
