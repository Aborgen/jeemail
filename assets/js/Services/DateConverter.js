// // Convert timestamp or Date object to localized Date object
// function Datey(date) {
//     // timestamp should be in milliseconds
//     const timestamp    = convertToMs(date);
//     const offset       = getOffset(timestamp);
//     const adjustedDate = timestamp + offset;
//
//     return msToDate(adjustedDate);
// }
//
// // Find hour difference from UTC to the client's reported timezone in ms
// function getOffset(dateObject) {
//     if(!(dateObject instanceof Date)) {
//         dateObject = msToDate(dateObject);
//     }
//
//     return -(dateObject.getTimezoneOffset() * 60 * 1000);
// }
//
// // Safely convert a millisecond value into a Date object
// function msToDate(ms) {
//     if(isNaN(ms)) {
//         throw new TypeError("msToDate only accepts numbers");
//     }
//
//     if(ms / 1000 < 1) {
//         throw new RangeError("msToDate requires that ms be in milliseconds (>= 1000)");
//     }
//
//     return new Date(ms);
// }
//
// function convertToMs(timestamp) {
//     if(!isNaN(timestamp)) {
//         if(timestamp / 1000 < 1) {
//             throw new RangeError("convertToMs requires that timestamp be in milliseconds (>= 1000)");
//         }
//
//         return timestamp;
//     }
//
//     else if(timestamp instanceof Date) {
//         return timestamp.getTime();
//     }
//
//     else {
//         throw new TypeError("timestamp is in an unsupported format");
//     }
// }
//
// const FULL_DATE          = 0;
// const MONTH_DAY          = 1;
// const MONTH_DAY_RELATIVE = 2;
// const TODAY              = 3;
//


function generateDateStringsHelper() {
    const dayBundle = {
        keys: ['en-US', 'en', 'en-GB'],
        obj: {
            full: [
                "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday",
                "Friday", "Saturday"
            ],
            short: [
                "Sun", "Mon", "Tue", "Wed", "Thur", "Fri", "Sat"
            ]
        }
    };

    const monthBundle = {
        keys: ['en-US', 'en', 'en-GB'],
        obj: {
            full: [
                "January", "February", "March", "April", "May", "June", "July",
                "August", "September", "October", "November", "December"
            ],
            short: [
                "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep",
                "Oct", "Nov", "Dec"
            ]
        }
    };
    const dayNames   = { locale: fooToCoo(dayBundle) };
    const monthNames = { locale: fooToCoo(monthBundle) };

    return { dayNames, monthNames };
}

function fooToCoo(foo) {
    const { keys, obj } = foo;
    let temp = {};
    keys.forEach((key) => {
        temp[key] = obj;
    });

    return temp;
}
//
// function formatDate(timestamp, is24HourTime = true, locale = "en") {
//     const date = !(timestamp instanceof Date) ? msToDate(timestamp) : timestamp;
//     const month = date.getMonth();
//     const monthShort = monthNames.locale[locale].short[month];
//
//     const day = date.getDay();
//     const dayShort = dayNames.locale[locale].short[day];
//
//     const dated = date.getDate();
//
//     const year = date.getFullYear();
//     const offset = Math.ceil((new Date().getTime() - date.getTime()) / 1000 / 60 / 60 / 24);
//     const ymd = locale === "en-us" ? `${month + 1}/${dated}/${year}` : `${dated}/${month + 1}/${year}`
//     let minutes = date.getMinutes();
//     minutes = minutes > 0 ? minutes : "00"
//     let hour = date.getHours();
//     let time = `${hour}:${minutes}`;
//     if(!is24HourTime) {
//         if(hour > 12) {
//             time = `${hour - 12}:${minutes} pm`;
//         }
//         else if(hour === 12) {
//             time = `${hour}:${minutes} pm`;
//         }
//         else if(hour === 0) {
//             time = `12:${minutes} am`;
//         }
//         else {
//             time += " am";
//         }
//     }
//
//     console.log(`${dayShort}, ${monthShort} ${dated}, ${year} at ${time}`);
//     console.log(`${monthShort} ${dated} (${offset} days ago)`);
//     console.log(`${time}`);
//     console.log(`${monthShort} ${dated}`);
//     console.log(ymd);
//     // Tue, Jul 24, 2018 at 1:30 PM
//     // Jul 12 (12 days ago)
//     // 1:33 pm
//     // Jul 23
//     // 12/21/17
// }

// TODO: Rename every time I use self to summin' else
class DateConverter {
    constructor(timestamp, is24HourTime = false) {
        this.is24HourTime = typeof is24HourTime === "boolean"
            ? is24HourTime
            : false;
        this.timeElapsed = 0;
        this.year        = {full:0, short:0};
        this.date        = 0;
        this.month       = { str:{ full:'', short:'' }, int:0 };
        this.day         = { str:{ full:'', short:'' }, int:0 };
        this.hours       = 0;
        this.minutes     = 0;

        this.locale      = '';
        this.era         = {};
        this._setLocale();

        if(typeof timestamp === 'undefined') {
            this.era = new Date(0);
        }

        else {
            this.setEra(timestamp);
        }
    }

    static get DATE_UNITS() {
        return {
            second: 1000,
            minute: 60000,
            hour: 3600000,
            day: 86400000,
            year: 31557600000
        };
    }

    static get MIN_SECONDS() { return 30; }
    static get MAX_DAYS()    { return 30; }

    static get FULL_TIMESTAMP()     { return 0; }
    static get PARTIAL_TIMESTAMP()  { return 1; }
    static get RELATIVE_TIMESTAMP() { return 2; }
    static get YMD()                { return 3; }

    getEra() {
        return this.era;
    }

    setEra(era) {
        if(!(era instanceof Date)) {
            this.era = this._msToDate(era);
            this.processEra();
            return;
        }

        this.era = era;
        this.processEra();
        return;
    }

    getLocale() {
        return this.locale;
    }

    _setLocale() {
        const { language, browserLanguage, languages } = navigator;
        if(language) {
            this.locale = language;
        }
        else if(languages) {
            this.locale = languages[0];
        }
        else if(browserLanguage) {
            this.locale = browserLanguage;
        }
        else {
            this.locale = "en";
        }

        return;
    }

    getEraMs() {
        return this.era.getTime();
    }

    toggle24HourTime() {
        this.is24HourTime = !this.is24HourTime;
        this.processEra();
        return;
    }

    // Safely convert a millisecond value into a Date object
    _msToDate(ms) {
        if(typeof ms !== 'number') {
            ms = parseInt(ms);
        }

        if(isNaN(ms)) {
            // console.log(ms);
            // console.log(typeof ms);
            throw new TypeError("_msToDate only accepts numbers");
        }

        if(ms / this.constructor.DATE_UNITS.second < 1) {
            throw new RangeError("_msToDate requires that ms be in milliseconds (>= 1000)");
        }

        return new Date(ms);
    }

    // Store various parts of the Date object in state
    processEra() {
        const { era, locale }          = this;
        const { dayNames, monthNames } = generateDateStringsHelper();
        // Year
        this.year.full = era.getFullYear();
        this.year.short = this.year.full.toString().slice(-2);
        // Month
        const month = era.getMonth();
        this.month.int       = month + 1;
        this.month.str.full  = monthNames.locale[locale].full[month];
        this.month.str.short = monthNames.locale[locale].short[month];
        // Day
        const day = era.getDay();
        this.day.int       = day + 1;
        this.day.str.full  = dayNames.locale[locale].full[day];
        this.day.str.short = dayNames.locale[locale].short[day];
        // Date
        this.date = era.getDate();
        // Offset
        this.timeElapsed = this._calculateTimeSinceEra();
        // Time
        const minutes = era.getMinutes();
        this.minutes = minutes > 0 ? minutes : "00";
        this.hours   = era.getHours();

        return;
    }

    _calculateTimeSinceEra() {
        const self    = this.constructor;
        const now     = new Date();
        const deltaMs = now.getTime() - this.getEraMs();
        const units   = self.DATE_UNITS;
        // If there is less than 1 second or less than MIN_SECONDS
        if(deltaMs < units.second ||
           deltaMs < (self.MIN_SECONDS * units.second)) {
            // Just now
            return 0;
        }
        // If there is less than 60 seconds
        else if(deltaMs < units.minute) {
            // Seconds ago
            const seconds = Math.floor(deltaMs / units.second);
            return { seconds };
        }
        // If there is less than 60 minutes
        else if(deltaMs < units.hour) {
            // Minutes ago
            const minutes = Math.floor(deltaMs / units.minute);
            return { minutes };
        }
        // If there is less than 24 hours
        else if(deltaMs < units.day) {
            // Hours ago
            const hours = Math.floor(deltaMs / units.hour);
            return { hours };
        }
        // If there is less than or there is 30 days
        else if(deltaMs <= (self.MAX_DAYS * units.day)) {
            // Days ago
            const days = Math.floor(deltaMs / units.day);
            return { days };
        }
        // Relative time will not be used
        else if(deltaMs <= units.year) {
            return -1;
        }
        // Display date in mm/dd/yy or dd/mm/yy dependant on locale
        else {
            // mm/dd/yy | dd/mm/yy
            return false;
        }
    }

    formatedString(type) {
        // Tue, Jul 24, 2018 at 1:30 PM
        // Jul 12 (12 days ago)
        // 1:33 pm
        // Jul 23
        // 12/21/17
        const self = this.constructor;
        let time;
        if(!this.is24HourTime) {
            if(this.hours > 12) {
                time = `${this.hours - 12}:${this.minutes} pm`;
            }
            else {
                time = `${this.hours}:${this.minutes} am`;
            }
        }
        else {
            time = `${this.hours}:${this.minutes}`;
        }

        switch(type) {
            case self.FULL_TIMESTAMP:
                return `${this.day.str.short}, ${this.month.str.short} ${this.date}, ${this.year.full} at ${time}`;
            case self.PARTIAL_TIMESTAMP:
                return time;
            case self.RELATIVE_TIMESTAMP:
                if(this.timeElapsed === 0) {
                    return "Just now!";
                }
                else if(this.timeElapsed === -1) {
                    // Jan 01
                    return `${this.month.str.short} ${this.date}`;
                }
                else if(!this.timeElapsed) {
                    // 01/01/15
                    // Fall through to default
                }
                else {
                    // Jan 01 (10 days ago)
                    const unit = Object.keys(this.timeElapsed)[0];
                    return `${this.month.str.short} ${this.date} (${this.timeElapsed[unit]} ${unit} ago)`;
                }
            case self.YMD:
            // Fall through to default
            default:
                if(this.locale === 'en-US') {
                    return `${this.month.int}/${this.date}/${this.year.short}`;
                }

                return `${this.date}/${this.month.int}/${this.year.short}`;
        }
    }
}

Object.freeze(DateConverter);
export default DateConverter;
