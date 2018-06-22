import React, { Component } from 'react';

class Email extends Component {

    /**
     * @return array | false
     */
    tryRender(match, emails) {
        const { organizer, slug, url } = match.params;
        const emailKeys = Object.keys(emails);
        const hasEmails = organizer
            ? slug in emailKeys[organizer]
            : slug in emailKeys

        // If there are no emails, fetchEmails will be called, which will
        // attempt to update Jeemail's emails state. If the emails do not exist
        // server side, the state will not be updated and tryRender() will
        // return false.
        if(!hasEmails) {
            const fetchUrl = organizer ? `/${organizer}/${slug}` : `/${slug}`;
            const emailsPossible = this.props.fetchEmails(fetchUrl);
            if(!emailsPossible) {
                return false;
            }
        }

        return organizer ? emails[organizer][slug] : emails[slug];
    }

    render() {
        const { match, emails } = this.props;
        const renderable = this.tryRender(match, emails);
        return (
            <React.Fragment>
            { null }
            </React.Fragment>
        );
    }

}

export default Email;
