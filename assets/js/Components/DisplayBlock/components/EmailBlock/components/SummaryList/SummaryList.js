import React, { Component, Fragment } from 'react';

import Summary from './components/Summary/Summary';

import hasKeys       from '../../../../../../Services/hasKeys';
import DateConverter from '../../../../../../Services/DateConverter';

class SummaryList extends Component {

    /**
     * @return array | false
     */
    getSummaries() {
        const { emails, selectedEmails, setSelectedEmails, checkState,
                match } = this.props;
        const emailArray = checkState(emails, match);
        if(!emailArray) {
            // The email label/category does not exist in the database
            return false;
        }

        const converter = new DateConverter();
        return emailArray.map((email, i) => {
            const isSelected = selectedEmails.includes(i);
            converter.setEra(email.content.timestamp);
            return <Summary key               = { i }
                            email             = { email }
                            isSelected        = { isSelected }
                            setSelectedEmails = { setSelectedEmails }
                            timestamp         = { converter.formatedString(DateConverter.RELATIVE_TIMESTAMP) }
                            index             = { i } />
        });
    }

    render() {
        const { message } = this.props;
        const summariesAvailable = this.getSummaries();
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
                </table> ||
                  <p>{ message }</p>
                }
            </Fragment>
        );
    }
}

export default SummaryList;
