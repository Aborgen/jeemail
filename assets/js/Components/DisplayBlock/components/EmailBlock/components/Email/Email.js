import React, { Component } from 'react';
import PropTypes            from 'prop-types';

class Email extends Component {

    render() {
        const { email } = this.props;
        return (
                <tr className="email">
                    <td><input type="checkbox" name="select" checked = { false }></input></td>
                    <td>
                        <input type    ="checkbox"
                               name    ="starred"
                               checked = { email.starred } ></input>
                    </td>
                    <td>
                        <input type    ="checkbox"
                               name    ="important"
                               checked = { email.important } ></input>
                    </td>
                    <td className="name">{ email.username }</td>
                    <td>
                        <div className="email-title-body">
                            <span className="george">{ email.subject }</span>
                            <span className="george"> - { email.body }</span>
                        </div>
                    </td>
                    <td>&nbsp;</td>
                    <td><span>{ email.timeSent }</span></td>
                </tr>
        );
    }
}

export default Email;

Email.propTypes = {
    email: PropTypes.shape({
        id      : PropTypes.number.isRequired,
        username: PropTypes.string.isRequired,
        subject : PropTypes.string.isRequired,
        body    : PropTypes.string.isRequired,
        timeSent: PropTypes.string.isRequired
    }).isRequired
}
