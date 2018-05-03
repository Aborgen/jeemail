import React, { Fragment, Component } from 'react';
import PropTypes                      from 'prop-types';

import DropDown from '../../../DropDown/DropDown';

class InputTools extends Component {

    getTrigger() {
        return (
            <span>&#9660;</span>
        );
    }

    getContent() {
        const links = this.props.nav.map((link) => {
            return <li key={link.id}>{link.name}</li>
        });

        return (
            <ol>
                { links }
            </ol>
        );
    }

    render() {
        return (
            <div className="languageConfig">
                <div className="virtualKeyboard">
                    <span>&#9000;</span>
                </div>
                <DropDown parentName    = { this.props.componentName }
                          componentName = { "inputTools" }
                          trigger       = { this.getTrigger() }
                          content       = { this.getContent() } />
            </div>
        );
    }
}

export default InputTools;

InputTools.defaultProps = {
    nav: [
        {   id: 0,
            name: "English",
            url: ""
        },
        {   id: 1,
            name: "English Dvorak",
            url: ""
        },
        {   id: 2,
            name: "English",
            url: ""
        },
        {   id: 3,
            name: "InputTools Settings",
            url: ""
        }
    ]
}

InputTools.propTypes = {
    componentName: PropTypes.string.isRequired,
    nav          : PropTypes.array.isRequired
}
