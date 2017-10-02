import React, { Component } from 'react';

//Components
import Email                from '../Email/Email';
import EmailList            from './components/EmailList/EmailList';
import getEmails            from '../../Services/GetEmails';

class EmailBlock extends Component {
    constructor() {
        super();
        this.state = {
            emails: [],
            emails_available: false
        };

        getEmails().then(emails => {
            this.setState({emails, emails_available: true});
        });
    }

    componentWillReceiveProps(nextProps) {
        // nextProps.refreshEmails.then(emails => {
        //     this.setState({emails, emails_available: true});
        // });
    }

    // refresh(fresh) {
    //     this.setState({emails: fresh, emails_available: true});
    //     console.log(fresh);
    // }

    render() {
        const emails = this.state.emails.map((email) => {
            return <Email key={email.id} email={email} />
        });

        return (
            <div className="mainBlock">
                {this.state.emails_available && <EmailList emails={emails} />}
            </div>
        );
    }

}

export default EmailBlock;
