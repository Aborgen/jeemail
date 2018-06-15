import React, { Fragment, PureComponent } from 'react';
import PropTypes                          from 'prop-types';

class TextInput extends PureComponent {

    generateTextInputs(fields) {
        const textInputs = fields.map((field, i) => {
            return (
                <Fragment key = { i } >
                    {
                        field.label !== undefined &&
                        <label htmlFor = { field.name } >
                            { field.label }
                        </label>
                    }
                    <input type        = "text"
                           name        = { field.name }
                           id          = { field.name }
                           placeholder = { field.text } />
                </Fragment>
            );
        });

        return textInputs;
    }

    render() {
        const { fields } = this.props;

        return (
            <Fragment>
                { this.generateTextInputs(fields) }
            </Fragment>
        );
    }
}

export default TextInput;

TextInput.propTypes = {
    fields: PropTypes.arrayOf(PropTypes.shape({
        name : PropTypes.string.isRequired,
        text : PropTypes.string,
        label: PropTypes.string
    }).isRequired).isRequired
}
