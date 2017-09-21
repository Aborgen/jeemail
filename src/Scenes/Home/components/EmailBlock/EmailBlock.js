import React, { Component } from 'react';

//Components
import Email from '../../../../Components/Email/Email';

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
