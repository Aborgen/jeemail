class FetchService {
    constructor(_this) {
        this.fetchBlocked    = this.fetchBlocked.bind(_this);
        this.fetchContacts   = this.fetchContacts.bind(_this);
        this.fetchMember     = this.fetchMember.bind(_this);
        this.fetchOrganizers = this.fetchOrganizers.bind(_this);
        this.fetchEmails     = this.fetchEmails.bind(_this);
    }

    static get BLOCKED() { return 0; }
    static get CONTACTS() { return 1; }
    static get MEMBER() { return 2; }
    static get ORGANIZERS() { return 3; }
    static get EMAILS() { return 4; }

    async fetchBlocked() {
        const content = await fetch('/api/member/blocked', {
            method: "POST",
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json'
            },
            body: { blocked: JSON.stringify(this.state.blocked) }
        });
        const blocked = await content.json();
        this.setState({ blocked });
    }

    async fetchContacts() {
        const content = await fetch('/api/member/contacts', {
            method: "POST",
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json'
            },
            body: { contacts: JSON.stringify(this.state.contacts) }
        });
        const contacts = await content.json();
        this.setState({ contacts });
    }

    async fetchMember() {
        const content = await fetch('/api/member/details', {
            method: "POST",
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        const member = await content.json();
        this.setState({ member });
    };

    async fetchOrganizers() {
        const content = await fetch('/api/member/organizers', {
            method: "POST",
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json'
            },
            body: { organizers: JSON.stringify(this.state.organizers) }
        });
        const organizers = await content.json();
        this.setState({ organizers });
    }

    async fetchEmails(string) {

        const url = string === null
            ? '/email/Inbox'
            : `/email/${string}`;
        const content = await fetch(url, {
            method: "POST",
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        const emails = await content.json();
        this.setState({ emails });
    }

    async fetch(type, string = null) {
        const self = this.constructor;
        switch (type) {
            case self.BLOCKED:
                return await this.fetchBlocked();
            case self.CONTACTS:
                return await this.fetchContacts();
            case self.MEMBER:
                return await this.fetchMember();
            case self.ORGANIZERS:
                return await this.fetchOrganizers();
            case self.EMAILS:
                return await this.fetchEmails(string);
            default:
                return null;
        };
    }
}

Object.freeze(FetchService);
export default FetchService;
