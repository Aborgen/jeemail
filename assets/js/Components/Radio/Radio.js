import React, { Fragment, PureComponent } from 'react';
import PropTypes                          from 'prop-types';

class Radio extends PureComponent {

    generateRadios(fields) {
        const radios = fields.map((field, i) => {
            return (
                <Fragment key = { i } >
                    <input type = "radio"
                           name = { field.name }
                           id   = { field.name } />
                   {
                       field.label !== undefined &&
                       <label htmlFor = { field.name } >
                           { field.label }
                       </label>
                   }
                </Fragment>
            );
        });

        return radios;
    }

    render() {
        const { parentName, fields, legend } = this.props;
        const classes = parentName !== undefined
            ? `radioGroup ${parentName}RadioGroup`
            : `radioGroup`;

        return (
            <fieldset className = { classes } >
            {
                legend !== undefined &&
                <legend>{ legend }</legend>
            }
                { this.generateRadios(fields) }
            </fieldset>
        );
    }
}

export default Radio;

Radio.propTypes = {
    parentName: PropTypes.string.isRequired,
    fields    : PropTypes.arrayOf(PropTypes.shape({
        name : PropTypes.string.isRequired,
        label: PropTypes.string
    }).isRequired).isRequired,
    legend    : PropTypes.string
}
