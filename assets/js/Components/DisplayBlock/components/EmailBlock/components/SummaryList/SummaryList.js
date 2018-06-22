import React, { Component, Fragment } from 'react';

import Summary from './components/Summary/Summary';

import hasKeys from '../../../../../../Services/hasKeys';

class SummaryList extends Component {

    /**
     * @return array | false
     */
    getSummaries() {
        const { emails, selectedEmails, setSelectedEmails, fetchEmails,
                match } = this.props;
        const { organizer, slug, url } = match.params;
        const hasEmails = emails[organizer]
            ? slug in emails[organizer]
            : slug in emails
        console.count(hasEmails);
        // If there are no emails, fetchEmails will be called, which will
        // attempt to update Jeemail's emails state. If the emails do not exist
        // server side, the state will not be updated and getSummaries() will
        // return false.
        if(!hasEmails) {
            const fetchUrl = organizer ? `/${organizer}/${slug}` : `/${slug}`;
            fetchEmails(fetchUrl);
            return false;
        }

        const emailArray = emails[organizer] ? emails[organizer][slug] : emails[slug];
        return emailArray.map((email, i) => {
            const isSelected = selectedEmails.includes(i);
            return <Summary key               = { i }
                            email             = { email }
                            isSelected        = { isSelected }
                            setSelectedEmails = { setSelectedEmails }
                            index             = { i } />
        });
    }

    render() {
        const { emails, message } = this.props;
        const summariesAvailable  = this.getSummaries();
        return (
            <Fragment>
                { summariesAvailable &&
                <table className="emailList">
                    <colgroup>
                        <col span="3" className="select" />
                        <col className="emailName" />
                        <col className="emailTitle" />
                        <col className="whitespace" />
                        <col className="dateTag" />
                    </colgroup>
                    <tbody>
                        { summariesAvailable }
                    </tbody>
                </table>             ||
                <p>{ message }</p>
                }
            </Fragment>
        );
    }
}

export default SummaryList;
