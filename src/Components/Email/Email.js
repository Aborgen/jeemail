import React, { Component } from 'react';
import PropTypes            from 'prop-types';

class Email extends Component {

    render() {
        return (
                <tr className="email">
                    <td><input type="checkbox" name="select" checked={this.props.email.checked}></input></td>
                    <td><input type="checkbox" name="favorite"></input></td>
                    <td><input type="checkbox" name="tag"></input></td>
                    <td>{this.props.email.id}</td>
                    <td>
                        <div className="email-title-body">
                            <span className="george">{this.props.email.title}</span>
                            <span className="george"> - {this.props.email.body}</span>
                        </div>
                    </td>
                    <td>&nbsp;</td>
                    <td><span>June {this.props.email.id} 2017</span></td>
                </tr>
        );
    }
}

export default Email;

Email.propTypes = {
    email: PropTypes.shape({
        userId : PropTypes.number.isRequired,
        id     : PropTypes.number.isRequired,
        title  : PropTypes.string.isRequired,
        body   : PropTypes.string.isRequired
    }).isRequired
}
