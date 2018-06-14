import React, { Component } from 'react';

import FullEmail from './components/FullEmail/FullEmail';
import Summary   from './components/Summary/Summary';

class EmailBlock extends Component {

    render() {
        const { emails, selectedEmails, setSelectedEmails } = this.props;
        const summaries = emails.Inbox.map((email, i) => {
            const isSelected = selectedEmails.includes(i);
            return <Summary key               = { i }
                            email             = { email }
                            isSelected        = { isSelected }
                            setSelectedEmails = { setSelectedEmails }
                            index             = { i } />
        });

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
                    { summaries }
                </tbody>
            </table>
        );
    }

}

export default EmailBlock;
