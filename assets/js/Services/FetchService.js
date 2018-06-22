import fetchWithTimeout from './fetchWithTimeout';
import objectArrayDiff  from './objectArrayDiff';

class FetchService {
    constructor(_this) {
        this.fetchBlocked    = this.fetchBlocked.bind(_this);
        this.fetchContacts   = this.fetchContacts.bind(_this);
        this.fetchMember     = this.fetchMember.bind(_this);
        this.fetchOrganizers = this.fetchOrganizers.bind(_this);
        this.fetchEmails     = this.fetchEmails.bind(_this);
        this.checkAuthorization = this.checkAuthorization.bind(_this);
    }


    static get SUCCESS_MESSAGE() { return "Loading..."; }
    static get ERROR_MESSAGE() { return "There seems to have been a problem"; }
    static get BLOCKED()     { return 0; }
    static get CONTACTS()    { return 1; }
    static get MEMBER()      { return 2; }
    static get ORGANIZERS()  { return 3; }
    static get EMAILS()      { return 4; }
    static get MAX_RETRIES() { return 3; }
    static get TIMEOUT()     { return 10000; } // 10 seconds
    static get URL()         { return 'https://api.jeemail.com'; }
    static get REQ()         {
        return {
            method: "POST",
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json'
            }
        };
    }

    async fetchBlocked() {
        const res = await fetch(`${ FetchService.URL }/member/blocked`,
            FetchService.REQ);
        const blocked = await res.json();
        this.setState({ blocked });
    }

    async fetchContacts() {
        const res = await fetch(`${ FetchService.URL }/member/contacts`,
            FetchService.REQ);
        const contacts = await res.json();
        this.setState({ contacts });
    }

    async fetchMember() {
        const res = await fetch(`${ FetchService.URL }/member/details`,
            FetchService.REQ);
        const member = await res.json();
        this.setState({ member });
    };

    async fetchOrganizers() {
        const res = await fetch(`${ FetchService.URL }/member/organizers`,
            FetchService.REQ);
        const organizers = await res.json();
        this.setState({ organizers });
    }

    async fetchEmails(string, organizer = null) {
        const url = organizer === null
        ? FetchService.URL + "/email" + string
        : FetchService.URL + "/email" + organizer + string;
        try {
            const res = await fetchWithTimeout(url, FetchService.REQ,
                FetchService.TIMEOUT);
            const emails     = await res.json();
            const prevEmails = this.state.emails[string.substring(1)] || [];
            const newEmails  = emails[string.substring(1)]            || [];
            const stateHasChanged = objectArrayDiff(prevEmails, newEmails);
            if(stateHasChanged) {
                this.setState({ emails });
            }

            return true;
        }
        catch (e) {
            console.log(e);
            return false;
        }
    }

    async checkAuthorization() {
        try {
            const response
                = await fetchWithTimeout(`${ FetchService.URL }/status`,
                    FetchService.REQ, FetchService.TIMEOUT);

            if(!response.ok) {
                redirect: window.location.replace('/login.php');
                throw new Error("Unauthorized");
            }

            else {
                this.setState({
                    authorized: true,
                    message: FetchService.SUCCESS_MESSAGE
                });
            }
        }
        catch (e) {
            this.setState({
                authorized: false,
                message: FetchService.ERROR_MESSAGE
            });
        }
    }

    fetch(type) {
        const self = this.constructor;
        switch (type) {
            case self.BLOCKED:
                return () => this.fetchBlocked();
            case self.CONTACTS:
                return () => this.fetchContacts();
            case self.MEMBER:
                return () => this.fetchMember();
            case self.ORGANIZERS:
                return () => this.fetchOrganizers();
            case self.EMAILS:
                return (string) => this.fetchEmails(string);
            default:
                return null;
        };
    }
}

Object.freeze(FetchService);
export default FetchService;
