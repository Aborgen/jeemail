import React, { Component } from 'react';

//Components
import Email                    from './components/Email/Email';
import { getReceived, getSent } from '../../../../Services/GetEmails';

class EmailBlock extends Component {
    constructor() {
        super();
        this.state = {
            emails_available: false
        };

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
        console.log(email);
        const emails = this.props.emails.map((email) => {
            return <Email key={email.id} email={ email } />
        });

        return (
            <div className="mainBlock">
                <table className="emailList">
                    <colgroup>
                        <col span="3" className="select" />
                        <col className="emailName" />
                        <col className="emailTitle" />
                        <col className="whitespace" />
                        <col className="dateTag" />
                    </colgroup>
                    <tbody>
                        {emails}
                    </tbody>
                </table>
            </div>
        );
    }

}

export default EmailBlock;
