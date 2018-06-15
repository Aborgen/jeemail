import React, { Component } from 'react';
import PropTypes            from 'prop-types';

import Button    from '../Button/Button';
import Radio     from '../Radio/Radio';
import TextInput from '../TextInput/TextInput';

import hasKeys   from '../../Services/hasKeys';

class Form extends Component {

    generateInput(bundles, componentName) {
        const classes = componentName !== undefined
            ? `mixedInputGroup ${componentName}mixedInputGroup`
            : `mixedInputGroup`;

        return bundles.map((bundle, i) => {
            const { textInputs, radios, legend } = bundle;
            return (
                <fieldset className = { classes }
                          id        = { `${componentName}MixedInputGroup${i}` }
                          key       = { i } >
                    {
                        legend !== undefined &&
                        <legend>{ legend }</legend>
                    }
                    {
                        hasKeys(textInputs) &&
                        <TextInput fields = { textInputs.fields } />
                    }
                    {
                        hasKeys(radios) &&
                        <Radios fields        = { radios.fields }
                                parentName    = { componentName }
                                legend        = { radios.legend } />
                    }
                </fieldset>
            );
        });
    }

    render() {
        const { componentName, method,
                bundles, buttonText } = this.props;
        const classes = componentName !== undefined
            ? `form ${componentName}Form`
            : `form`;
        const formMethod = method !== undefined
            ? method
            : "GET";

        return (
            <div className = { classes } >
                <form method = { formMethod } >
                    { this.generateInput(bundles, componentName) }
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
    buttonText   : PropTypes.string.isRequired,
    bundles      : PropTypes.arrayOf(PropTypes.shape({
        legend    : PropTypes.string,
        textInputs: PropTypes.shape({
            fields: PropTypes.arrayOf(PropTypes.shape({
                name : PropTypes.string.isRequired,
                label: PropTypes.string,
                text : PropTypes.string
            }).isRequired)
        }),
        radios    : PropTypes.shape({
            fields: PropTypes.arrayOf(PropTypes.shape({
                name : PropTypes.string.isRequired,
                label: PropTypes.string
            }).isRequired),
            legend: PropTypes.string
        })
    }).isRequired).isRequired
}
