import React, { Component } from 'react';

class EmailList extends Component {

    render() {
        return (
            <table className="emailList">
                <colgroup>
                    <col span="3" className="select" />
                    <col className="emailName" />
                    <col className="emailTitle" />
                    <col className="whitespace" />
                    <col className="dateTag" />
                </colgroup>
                <tbody>
                    {this.props.emails}
                </tbody>
            </table>
        );
    }

}

export default EmailList;
