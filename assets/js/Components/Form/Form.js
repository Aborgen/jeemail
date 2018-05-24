import React, { PureComponent } from 'react';
import PropTypes                from 'prop-types';

import Button from '../../Components/Button/Button';

class Form extends PureComponent {

    generateInput(fields) {
        const elements = fields.map((field, index) => {
            return (
                <div className = { `formGroup ${field.name}FormGroup` }
                     key       = { index } >
                    {
                        field.label !== undefined &&
                        <label htmlFor = { field.name } >
                            { field.label }
                        </label>
                    }
                    <input type        = { field.type }
                           name        = { field.name }
                           id          = { field.name }
                           placeholder = { field.text } ></input>
                </div>
            );
        });

        return elements;
    }

    render() {
        const { componentName, method, fields, buttonText } = this.props;
        const classes = componentName !== undefined
            ? `form ${componentName}Form`
            : `form`;
        const formMethod = method !== undefined
            ? method
            : "GET";

        return (
            <div className = { classes } >
                <form method = { formMethod } >
                    { this.generateInput(fields) }
                    <Button type = { "submit" }
                            name = { "submit" }
                            text = { buttonText } />
                </form>
            </div>
        );
    }
}

export default Form;

Form.propTypes = {
    componentName: PropTypes.string.isRequired,
    method       : PropTypes.string.isRequired,
    fields       : PropTypes.arrayOf(PropTypes.shape({
        name : PropTypes.string.isRequired,
        type : PropTypes.string.isRequired,
        label: PropTypes.string,
        text : PropTypes.string
    }).isRequired).isRequired,
    buttonText   : PropTypes.string.isRequired
}
