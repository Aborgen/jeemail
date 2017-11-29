import React, { Component } from 'react';
import PropTypes            from 'prop-types';

class Email extends Component {

    render() {
        return (
                <tr className="email">
                    <td><input type="checkbox" name="select" checked={this.props.email.checked}></input></td>
                    <td><input type="checkbox" name="starred"></input></td>
                    <td><input type="checkbox" name="important"></input></td>
                    <td className="name">{this.props.email.username}</td>
                    <td>
                        <div className="email-title-body">
                            <span className="george">{this.props.email.subject}</span>
                            <span className="george"> - {this.props.email.body}</span>
                        </div>
                    </td>
                    <td>&nbsp;</td>
                    <td><span>{this.props.email.time}</span></td>
                </tr>
        );
    }
}

export default Email;

Email.propTypes = {
    email: PropTypes.shape({
        id       : PropTypes.number.isRequired,
        username : PropTypes.string.isRequired,
        subject  : PropTypes.string.isRequired,
        body     : PropTypes.string.isRequired,
        time     : PropTypes.string.isRequired
    }).isRequired
}
