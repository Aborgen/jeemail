import React, { Component } from 'react';
import PropTypes            from 'prop-types';

class FullEmail extends Component {

    render() {
        return (
            <div></div>
        );
    }

}

export default FullEmail;

FullEmail.propTypes = {
    email: PropTypes.shape({
        id       : PropTypes.number.isRequired,
        important: PropTypes.bool.isRequired,
        starred  : PropTypes.bool.isRequired,
        category : PropTypes.shape({
            id        : PropTypes.number.isRequired,
            visibility: PropTypes.bool.isRequired,
            category  : PropTypes.shape({
                id  : PropTypes.number.isRequired,
                name: PropTypes.string.isRequired,
                slug: PropTypes.string.isRequired
            }).isRequired,
        }).isRequired,
        labels   : PropTypes.shape({
            id           : PropTypes.number.isRequired,
            defaultLabels: PropTypes.shape({
                id        : PropTypes.number.isRequired,
                visibility: PropTypes.bool.isRequired,
                label     : PropTypes.shape({
                    id  : PropTypes.number.isRequired,
                    name: PropTypes.string.isRequired,
                    slug: PropTypes.string.isRequired
                }).isRequired,
            }).isRequired,
            labels: PropTypes.arrayOf(PropTypes.shape({
                id        : PropTypes.number.isRequired,
                visibility: PropTypes.bool.isRequired,
                label     : PropTypes.shape({
                    id  : PropTypes.number.isRequired,
                    name: PropTypes.string.isRequired,
                    slug: PropTypes.string.isRequired
                }).isRequired,
            }).isRequired).isRequired // NOTE: It's possible there are no labels
        }).isRequired
    }).isRequired
}
