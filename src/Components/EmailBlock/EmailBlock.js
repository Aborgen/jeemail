import React, { Component } from 'react';

//Components
import EmailList            from './components/EmailList/EmailList';
import Email                from '../Email/Email';

class EmailBlock extends Component {
    constructor() {
        super();
        this.state = {emails: []};

        fetch('https://jsonplaceholder.typicode.com/posts')
            .then((res) => res.json())
            .then((posts) => {
                this.setState({emails: posts})
            })
            .catch((err) => console.log(err))
    }

    render() {
        const emails = this.state.emails.map((email) => {
            return <Email key={email.id} email={email} />
        });

        return (
            <div className="mainBlock">
                <EmailList emails={emails} />
            </div>
        );
    }

}

export default EmailBlock;
