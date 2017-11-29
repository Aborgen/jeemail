import React, { Component } from 'react';

//Components
import Email                   from './components/Email/Email';
import EmailList               from './components/EmailList/EmailList';
import { getReceived, getSent} from '../../Services/GetEmails';

class EmailBlock extends Component {
    constructor() {
        super();
        this.state = {
            emails: [],
            emails_available: false
        };

        getReceived(3).then((emails) => {
            this.setState({emails, emails_available: true});
        });

        this.selectionOpt = this.selectionOpt.bind(this);
    }

    selectionOpt(checkStatus) {

        switch (checkStatus) {
            case "All":

                break;
            case "None":

                break;
            case "Read":

                break;
            case "Unread":

                break;
            case "Starred":

                break;
            case "Unstarred":

                break;
            default:
                break;

        }
    }

    componentWillReceiveProps(nextProps) {
        // if(nextProps.refreshEmails) {
        //     nextProps.refreshEmails.then(emails => {
        //         this.setState({emails, emails_available: true});
        //     });
        // }
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
