import React, { Component } from 'react';
import PropTypes            from 'prop-types';

import DateConverter from '../../../../../../../../Services/DateConverter';

class Summary extends Component {

    render() {
        const { email, setSelectedEmails, isSelected, index } = this.props;
        const timestamp = email.content.timestamp;
        const converter = new DateConverter(timestamp);
        return (
                <tr className="email">
                    <td>
                        <input defaultChecked = { isSelected }
                               type           = "checkbox"
                               name           = "select"
                               onClick = {
                                   (e) => setSelectedEmails(e.target.checked, index)
                               } >
                        </input>
                    </td>
                    <td>
                        <input defaultChecked = { email.starred }
                               type           = "checkbox"
                               name           = "starred" >
                        </input>
                    </td>
                    <td>
                        <input defaultChecked = { email.important }
                               type           = "checkbox"
                               name           = "important" >
                        </input>
                    </td>
                    <td>{ email.content.reply_to_email }</td>
                    <td>
                        <div className="email-title-body">
                            <span className="george">{ email.content.subject }</span>
                            <span className="george"> - { email.content.body }</span>
                        </div>
                    </td>
                    <td>&nbsp;</td>
                    <td>
                        <span>
                            { converter.formatedString(DateConverter.RELATIVE_TIMESTAMP) }
                        </span>
                    </td>
                </tr>
        );
    }
}

export default Summary;

Summary.propTypes = {
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
