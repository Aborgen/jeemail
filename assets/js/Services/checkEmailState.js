function checkEmailState(fetchEmails) {
    return (emails, match) => {
        const { organizer, slug, url } = match.params;
        const hasEmails = emails[organizer]
            ? slug in emails[organizer]
            : slug in emails
        // If there are no emails, fetchEmails will be called, which will
        // attempt to update Jeemail's emails state. If the emails do not exist
        // server side, return false.
        if(!hasEmails) {
            const fetchUrl = organizer ? `/${organizer}/${slug}` : `/${slug}`;
            fetchEmails(fetchUrl);
            return false;
        }

        return emails[organizer] ? emails[organizer][slug] : emails[slug];
    }
}

export default checkEmailState;
